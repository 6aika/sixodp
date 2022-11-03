# CKAN

Doing a CKAN version upgrade starts by changing the version number [here](https://github.com/6aika/sixodp/blob/master/ansible/roles/ckan-install/vars/main.yml). Next the developer should run the ansible playbook and check if it will succeed or not. Those [patch files](https://github.com/6aika/sixodp/tree/master/ansible/roles/ckan-install-patches/files/patches) that doesn't change anything should be removed and those that fail, should be ported to newer CKAN version.

If the playbook succeeds, the following list of features should be checked that they still work. There are no automated tests.

* Creating, modifying and deleting user
* Requesting password reset for user
* Creating, modifying and deleting dataset
* Setting a reminder date to dataset and checking that the reminder is actually sent
* Subscribing to dataset updates anonymously and checking that notifications are sent
* Creating, modifying and deleting organization
* Creating, modifying and deleting category \(group in ckan terms\)
* Creating, modifying and deleting showcase
* Creating, modifying and deleting collection
* Submitting dataset anonymously
* Submitting showcase anonymously
* Modifying different fields in dataset via mass editor
* Fetching google analytics data via paster cli command

