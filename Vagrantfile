# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.define "sixodp", primary: true do |server|
    server.vm.box = "bento/ubuntu-16.04"
    server.vm.network :private_network, ip: "10.106.10.10"
    server.vm.hostname = "sixodp"

    case RUBY_PLATFORM
    when /mswin|msys|mingw|cygwin|bccwin|wince|emc/
      # Fix Windows file rights, otherwise Ansible tries to execute files
      server.vm.synced_folder ".", "/vagrant", type:"virtualbox", :mount_options => ["dmode=755","fmode=644"]
    end

    server.vm.provision "ansible_local" do |ansible|
      # Ansible is locked to version 2.3.2 as 2.4 is broken, once removed remember to remove lock from jenkins and cloudformation
      ansible.version = "2.3.2"
      ansible.install_mode = "pip"
      ansible.inventory_path = "inventories/vagrant"
      ansible.limit = "all"
      ansible.playbook = "deploy-all.yml"
      ansible.provisioning_path = "/vagrant/ansible"
      ansible.raw_arguments = ["-v"]
    end
    server.vm.provider "virtualbox" do |vbox|
      vbox.gui = false
      vbox.memory = 2048
      vbox.customize ["modifyvm", :id, "--nictype1", "virtio"]
    end
  end

  config.vm.define "multimain", autostart: false do |mainserver|
    mainserver.vm.box = "bento/ubuntu-16.04"
    mainserver.vm.network :private_network, ip: "10.106.10.20"
    mainserver.vm.hostname = "sixodp-multi-main"

    case RUBY_PLATFORM
    when /mswin|msys|mingw|cygwin|bccwin|wince|emc/
      # Fix Windows file rights, otherwise Ansible tries to execute files
      mainserver.vm.synced_folder ".", "/vagrant", type:"virtualbox", :mount_options => ["dmode=755","fmode=644"]
    end

    mainserver.vm.provision "ansible_local" do |ansible|
      ansible.version = "2.3.2"
      ansible.install_mode = "pip"
      ansible.inventory_path = "inventories/vagrant_multi"
      ansible.limit = "all"
      ansible.playbook = "deploy-mainserver.yml"
      ansible.provisioning_path = "/vagrant/ansible"
      ansible.raw_arguments = ["-v"]
    end
    mainserver.vm.provider "virtualbox" do |vbox|
      vbox.gui = false
      vbox.memory = 2048
      vbox.customize ["modifyvm", :id, "--nictype1", "virtio"]
    end
  end

  config.vm.define "multickan", autostart: false do |ckanserver|
    ckanserver.vm.box = "bento/ubuntu-16.04"
    ckanserver.vm.network :private_network, ip: "10.106.10.30"
    ckanserver.vm.hostname = "sixodp-multi-ckan"

    case RUBY_PLATFORM
    when /mswin|msys|mingw|cygwin|bccwin|wince|emc/
      # Fix Windows file rights, otherwise Ansible tries to execute files
      ckanserver.vm.synced_folder ".", "/vagrant", type:"virtualbox", :mount_options => ["dmode=755","fmode=644"]
    end

    ckanserver.vm.provision "ansible_local" do |ansible|
      ansible.version = "2.3.2"
      ansible.install_mode = "pip"
      ansible.inventory_path = "inventories/vagrant_multi"
      ansible.limit = "all"
      ansible.playbook = "deploy-ckanserver.yml"
      ansible.provisioning_path = "/vagrant/ansible"
      ansible.raw_arguments = ["-v"]
    end
    ckanserver.vm.provider "virtualbox" do |vbox|
      vbox.gui = false
      vbox.memory = 2048
      vbox.customize ["modifyvm", :id, "--nictype1", "virtio"]
    end
  end

end
