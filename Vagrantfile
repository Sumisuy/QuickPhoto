# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "sternpunkt/jimmybox-5"
    config.vm.network "private_network", ip: "192.168.100.100"
    config.vm.hostname = "dev.env"
    config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]

    config.vm.provision :shell, :path => "provision.sh"

end
