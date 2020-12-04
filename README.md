## Open Data Portal of the Six City Strategy

This repository provides the open data catalog of the [Six City Strategy](http://6aika.fi/in-english/) in Finland.

Live demo at https://demo.dataportaali.com

### Getting started

Prerequisites:

- [Vagrant](https://www.vagrantup.com/) (tested on 1.8.7)
- [VirtualBox](https://www.virtualbox.org/) (tested on 5.1.8)

Clone the repository and its submodules, and start Vagrant:

    git clone https://github.com/6aika/sixodp.git
    cd sixodp/
    git submodule update --init --recursive
    npm install
    npm run build
    vagrant up

Vagrant installs ansible inside the virtual machine so that Windows users can also automate local builds. Ansible is locked to version 2.3.2 as 2.4 was broken. If the lock is removed, remember to remove lock from jenkins and cloudformation. 
After [Ansible](http://www.ansible.com/) provisions the system, the service will be running in the virtual machine and is available from your host machine at https://10.106.10.10/

User credentials for an administrator are `admin:admin`, and `test:test` for a regular user.

To reprovision the server (i.e. to run Ansible) again:

    vagrant provision

You can ssh into the server:

    vagrant ssh

And you can also run Ansible manually inside the virtual machine:

    vagrant ssh
    cd /vagrant/ansible
    ansible-playbook -v -i inventories/vagrant deploy-all.yml
    
Multimachine vagrant environment can be build with:
    
    vagrant up multimain
    vagrant up multickan
    
Individual machines are in located in:
   
* Wordpress: 10.106.10.20
* CKAN: 10.106.10.30

You can SSH into them with:
    
    vagrant ssh multimain
    vagrant ssh multickan  

### Development

With Vagrant, the host machine shares the working directory into the virtual machine. The web server uses the CKAN extensions directly from the source code via symlinks. Depending on what you change however, some extra rules apply:

- If you edit a Jinja template, changes apply instantly (only page refresh required).
- If you edit styles in /shared you need to compile the modified SASS-files (`npm run dev`).
- If you edit Python code of the extensions, you need to restart the WSGI server (`vagrant ssh` and `sudo service apache2 restart`).
- If you edit Javascript, you need to run the frontend build to compile and minify files (`vagrant ssh`, `cd /vagrant/ansible` and `ansible-playbook -v -i inventories/vagrant frontend-build.yml`).

#### Pulling updates

If there have been updates to submodules, run following commands:

    git submodule sync (for making sure that submodule points at the corrent repository in the case that the repository has been forked)
    git submodule update --init --recursive

### SSL certificates

By default environments use self-signed certificates for HTTPS. To enable [Let's Encrypt](https://letsencrypt.org/) certificate add following to `environment-specific vars` ([Example](https://github.com/6aika/sixodp/blob/master/ansible/vars/environment-specific/generic-qa.yml#L35))

    acmetool_hostnames:
        - "{{ public_facing_hostname }}"
        - some_alternative_example_domain

### Repository structure

    .
    ├── ansible
    │   ├── deploy-all.yml                  Top-level playbook for configuring complete service
    │   ├── inventories                     Target server lists
    │   ├── roles                           Main configuration
    │   └── vars                            Variables common for all roles
    │       ├── common.yml                  Variables common for all roles and environments
    │       ├── environment-specific        Configuration specific for each deployment env
    │       └── secrets-defaults.yml        Default passwords, used in Vagrant
    ├── ckanext                             Custom CKAN extensions, main source directory
    ├── doc                                 Documentation
    ├── shared                              Files shared between CKAN and WordPress. Mainly for shared SASS-styles, which are compiled into separate css-files
    ├── sixodp                              WordPress related files
    └── Vagrantfile                         Configuration for local development environment

### Managing translations

Translations are decentralized to separate CKAN extensions. The main ckanext-sixodp_ui extension is used to manage modifications to CKAN core translations while other extensions
will contain only extension specific translations. 
The extensions follow the internalization conventions defined in [CKAN v2.6 docs](http://docs.ckan.org/en/ckan-2.6.0/extensions/translating-extensions.html).

Transifex is used to handle the translating work itself and Transifex Client for uploading Transifex resources and downloading new translation files. 
The project's Transifex page can be found [here](https://www.transifex.com/6aika-dataportal). 
A more thorough guide on how to update existing translations in practise is located in the ckanext-sixodp_ui extension's README-file.

### Support / Contact / Contribution

Please file a [new issue](https://github.com/6aika/sixodp/issues) at GitHub.

### Copying and License

This material is copyright by Tampere Region Economic Development Agency Tredea, and is based on earlier work by Population Register Centre and Finnish State Treasury.

CKAN-related source code such as the [CKAN extensions](/ckanext) and patches are licensed under the [AGPL v3.0](http://www.fsf.org/licensing/licenses/agpl-3.0.html). All other content in this repository is licensed under the MIT license.
