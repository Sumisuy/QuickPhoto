#!/usr/bin/env bash

# From Creating a Vagrant Box

touch /etc/profile.d/composer_global.sh
cat << EOF | sudo tee -a /etc/profile.d/on_terminal_load.sh
  echo ======================================================
  echo =
  echo =   CHANGELOG
  echo =   DATE ---- AUTHOR -- CHANGES
  echo =
  echo =   07/01/17 -- MS ---- Initial build
  echo =   07/01/17 -- MS ---- Enabled beanstalk & Seed
  echo =
  echo ======================================================
  echo =
  echo =   DEVELOPMENT ENVIRONMENT
  echo =   FOR QUICK-PHOTO
  echo =
  echo =   Application Path /var/www
  echo =   Webroot /var/www/public
  echo =
  echo =   AUTHOR - Martin Smith - MS
  echo =
  echo ======================================================
  echo ======================================================
EOF

ln -s /vagrant/projects
cat << EOF | sudo tee -a /etc/motd.tail
*****************************************************
* STARTING PROVISIONING FOR DEVELOPMENT ENVIRONMENT *
*****************************************************
EOF

echo ===================================
echo =
echo =   UPDATING
echo =
echo ===================================

sudo apt-get update

echo ===================================
echo =
echo =   GATHERING ESSENTIALS
echo =
echo ===================================

sudo apt-get install -y python-software-properties build-essential
sudo add-apt-repository -y ppa:ondrej/php5-5.6
sudo apt-get update

echo ===================================
echo =
echo =   INSTALL DEPENDANCIES
echo =
echo ===================================

sudo apt-get install -y pkg-config
sudo apt-get install -y libmagick9-dev libmagickcore-dev libmagickwand-dev

echo ===================================
echo =
echo =   INSTALLING PHP
echo =
echo ===================================

sudo apt-get install -y git-core subversion curl php5-cli php5-curl \
 php5-mcrypt php5-gd php5-json php-pear php5-dev

echo ===================================
echo =
echo =   INSTALLING COMPOSER
echo =
echo ===================================

cd /var/www
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

echo ===================================
echo =
echo =   CONFIGURING MYSQL
echo =
echo ===================================

sudo debconf-set-selections <<< 'mysql-server \
 mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server \
 mysql-server/root_password_again password root'
sudo apt-get install -y php5-mysql mysql-server

cat << EOF | sudo tee -a /etc/mysql/conf.d/default_engine.cnf
[mysqld]
default-storage-engine = MyISAM
EOF

sudo service mysql restart

echo ===================================
echo =
echo =   INSTALLING APACHE
echo =
echo ===================================

sudo apt-get install -y apache2 libapache2-mod-php5
cat << EOF | sudo tee -a /etc/apache2/apache2.conf
  ServerName dev.env
EOF
sudo sed -i 's/127.0.0.1 localhost/192.168.100.100 dev.env/' /etc/hosts
sudo a2enmod rewrite
sudo service apache2 restart

echo ===================================
echo =
echo =   INSTALLING PHP EXTENSIONS
echo =
echo ===================================

sudo apt-get install -y ghostscript php5-imagick
sudo php5enmod imagick
sudo service php5-fpm restart
sudo service apache2 restart

echo ===================================
echo =
echo =   SETUP WEBROOT LOCATION
echo =   ALLOW OVERIDES ON APACHE2.CONF
echo =
echo ===================================

sudo cp /var/www/scripts/000-default.conf /etc/apache2/sites-enabled/000-default.conf
sudo cp /var/www/scripts/qpbeanstalk.conf /etc/apache2/sites-available/qpbeanstalk.conf
sudo cp /var/www/scripts/apache2.conf /etc/apache2/apache2.conf
sudo a2ensite qpbeanstalk
echo .
sudo service apache2 restart

echo ===================================
echo =
echo =   CREATE DB TABLE
echo =
echo ===================================

sudo mysql -uroot -proot -e "CREATE DATABASE qphotodb"

echo ===================================
echo =
echo =   COMPOSER INSTALL
echo =
echo ===================================

cd /var/www/
sudo composer install

echo ===================================
echo =
echo =   MIGRATE AND SEED
echo =
echo ===================================

cd /var/www/
sudo php artisan migrate
sudo php artisan db:seed

echo ===================================
echo =
echo =   INSTALL BEANSTALKD
echo =
echo ===================================

sudo apt-get install -y beanstalkd
sudo cp /var/www/scripts/beanstalkd /etc/default/beanstalkd
sudo service beanstalkd restart

echo ===================================
echo =
echo =   INSTALL SUPERVISOR
echo =
echo ===================================

sudo apt-get install -y supervisor
sudo cp /var/www/scripts/quickphoto.conf /etc/supervisor/conf.d/quickphoto.conf
sudo service supervisor restart

echo ===================================
echo =
echo =   UPDATE PHP.INI
echo =
echo ===================================

sudo cp /var/www/scripts/php.ini /etc/php5/apache2/php.ini
sudo service apache2 restart

echo ===================================
echo =
echo =   CREATE PUBLIC STORAGE SYMLINK
echo =
echo ===================================

cd /var/www/
sudo ln -s /var/www/storage/app/public/ /var/www/public/storage

echo ===================================
echo =
echo =   FINAL UPDATE
echo =
echo ===================================

sudo apt-get update

echo ===================================
echo =
echo =   PROVISIONING SCRIPT COMPLETE
echo =
echo ===================================
