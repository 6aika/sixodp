# Installing updates

Code and other updates can installed to instance via running following command on shell prompt on the instance itself:

```text
sixodp-update
```

The script will fetch newest updates from github master branch and run ansible to install them. If there are no updates in github or the script has been previosly errored for some reason, ansible can be executed with following commands manually:

```text
sudo su
cd /root/sixodp/ansible  // this directory might be different depensing on the instance
ansible-playbook -i inventories/(instance_id) deploy-transitional.yml
```

Ansible will update OS packages and WordPress, updating CKAN will require manual work.

