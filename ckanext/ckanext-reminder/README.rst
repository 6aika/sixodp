=============
ckanext-reminder
=============

This extension provides simple email notifications for datasets which have a set data-expiry date. The extension relies on a
daily cronjob during which datasets are checked if the reminder-data is set to current date. The email sending process is not
retried in any way and the emails will not be sent if the cronjob fails to run for some reason. 


------------
Requirements
------------

This extension is developed and tested with CKAN version 2.5.2 but versions starting from 2.2 should work fine.


------------
Installation
------------

To install ckanext-reminder:

1. Activate your CKAN virtual environment, for example

     . /usr/lib/ckan/default/bin/activate

2. Install the ckanext-reminder Python package into your virtual environment

     pip install ckanext-reminder

3. Add ``reminder`` to the ``ckan.plugins`` setting in your CKAN
   config file (by default the config file is located at
   ``/etc/ckan/default/production.ini``).

4. Restart CKAN. For example if you've deployed CKAN with Apache on Ubuntu

     sudo service apache2 reload

5. Add the following field to your dataset. E.g. with ckanext-scheming

```
    {
        "field_name": "reminder",
        "label": "Next reminder date",
        "preset": "date",
        "display_snippet": null
    }
```

---------------
Config Settings
---------------

The extension supports one recipient for reminder emails. Required configs are

    ckanext.reminder.site_url = https://<YOUR_SITE_URL>
    ckanext.reminder.recipient_username = <YOUR_ADMIN_USERNAME>


------------------------
Development Installation
------------------------

To install ckanext-reminder for development, activate your CKAN virtualenv and
do

    git clone https://github.com/6aika/ckanext-reminder.git
    cd ckanext-reminder
    python setup.py develop
    pip install -r dev-requirements.txt
