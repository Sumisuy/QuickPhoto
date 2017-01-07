VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "precise64"
  config.vm.box_url = "https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box"
  config.vm.network :private_network, ip: "192.168.100.100"

  config.vm.synced_folder ".", "/var/www",
  owner: "www-data", group: "www-data"
  config.vm.hostname = "dev.env"
  config.vm.provider "virtualbox" do |v|
      v.memory = 4096
      v.cpus = 2
  end
  # The CORE provisioning script
  config.vm.provision :shell, :path => "scripts/provision.sh"
  
end