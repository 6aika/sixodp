from ckan.common import request
from pylons import config
from ckan import model
from ckan.lib.mailer import mail_user

import ckan.logic as logic
import datetime
import logging

log = logging.getLogger(__name__)

def send_email_reminders(context, data_dict):
    if request.environ.get('paste.command_request'):
        send_reminders()

def get_datasets_with_reminders():
    now = datetime.datetime.now()
    search_dict = {
        'fq': 'reminder:' + now.strftime("%Y-%m-%d")
    }

    return logic.get_action('package_search')({}, search_dict)

def send_reminders():

    items = get_datasets_with_reminders()

    try:
        username = config.get('ckanext.reminder.recipient_display_name')
        recipient = model.User.get(username)

        for item in items['results']:

            # Todo add localization
            message_body = 'This is a reminder of a dataset expiration: ' + config.get('ckanext.reminder.site_url') + '/dataset/' + item['name']
            mail_user(recipient, "CKAN reminder", message_body)

    except Exception, ex:
        log.exception(ex)
        raise
