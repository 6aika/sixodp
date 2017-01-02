# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.define "sixodp" do |server|
    server.vm.box = "bento/ubuntu-16.04"
    server.vm.network :private_network, ip: "10.106.10.10"
    server.vm.hostname = "sixodp"

    case RUBY_PLATFORM
    when /mswin|msys|mingw|cygwin|bccwin|wince|emc/
      # Fix Windows file rights, otherwise Ansible tries to execute files
      server.vm.synced_folder "./", "/src", type:"virtualbox", :mount_options => ["dmode=755","fmode=644"]
    else
      # Basic VM synced folder mount
      server.vm.synced_folder "", "/src"
    end

    server.vm.provision "ansible_local" do |ansible|
      ansible.inventory_path = "inventories/vagrant"
      ansible.limit = "all"
      ansible.playbook = "deploy-all.yml"
      ansible.provisioning_path = "/src/ansible"
    end
    server.vm.provider "virtualbox" do |vbox|
      vbox.gui = false
      vbox.memory = 2048
      vbox.customize ["modifyvm", :id, "--nictype1", "virtio"]
    end
  end
end
