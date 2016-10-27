import ckan.plugins as plugins
from pylons import config
import ckan.logic as logic
import ckan.plugins.toolkit as toolkit
from ckan.lib.mailer import mail_user
import datetime

class ReminderPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IConfigurable)
    plugins.implements(plugins.ITemplateHelpers)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'reminder')

    # IConfigurable

    def configure(self, config):
        # Raise an exception if required configs are missing
        required_keys = (
            'ckanext.reminder.site_url',
            'ckanext.reminder.recipient_email',
            'ckanext.reminder.recipient_display_name'
        )

        for key in required_keys:
            if config.get(key) is None:
                raise RuntimeError(
                    'Required configuration option {0} not found.'.format(
                        key
                    )
                )

    def get_datasets_with_reminders(self):
        now = datetime.datetime.now()
        search_dict = {
            'fq': 'reminder:' + now.strftime("%Y-%m-%d")
        }

        return logic.get_action('package_search')({}, search_dict)

    def send_reminders(self):

        items = self.get_datasets_with_reminders()

        class Recipient:
            email = config.get('ckanext.reminder.recipient_email')
            display_name = config.get('ckanext.reminder.recipient_display_name')

        for item in items['results']:

            # Todo add localization
            message_body = 'This is a reminder of a dataset expiration: ' + config.get('ckanext.reminder.site_url') + '/dataset/' + item['name']
            mail_user(Recipient, "CKAN reminder", message_body)

    # ITemplateHelpers
    def get_helpers(self):
        return {'send_reminders': self.send_reminders}

    
