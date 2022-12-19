# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.define "sixodp", primary: true do |server|
    server.vm.box = "bento/ubuntu-20.04"
    server.vm.network :private_network, ip: "10.106.10.10"
    server.vm.hostname = "sixodp"

    case RUBY_PLATFORM
    when /mswin|msys|mingw|cygwin|bccwin|wince|emc/
      # Fix Windows file rights, otherwise Ansible tries to execute files
      server.vm.synced_folder ".", "/vagrant", type:"virtualbox", :mount_options => ["dmode=755","fmode=644"]
    end


    server.vm.provision "shell", inline: "sudo apt-get update"

    server.vm.provision "ansible_local" do |ansible|
      ansible.install_mode = "pip3"
      #ansible.pip_install_cmd = "sudo apt-get install -y python3-distutils && curl -s https://bootstrap.pypa.io/get-pip.py | sudo python3"
      ansible.inventory_path = "inventories/vagrant"
      ansible.limit = "all"
      ansible.playbook = "deploy-all.yml"
      ansible.provisioning_path = "/vagrant/ansible"
      ansible.raw_arguments = ["-v"]
    end
    server.vm.provider "virtualbox" do |vbox|
      vbox.gui = false
      vbox.memory = 3048
      vbox.customize ["modifyvm", :id, "--nictype1", "virtio"]
    end
  end


end
