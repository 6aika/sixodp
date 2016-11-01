import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
from ckanext.reminder.logic import action

class ReminderPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IConfigurable)
    plugins.implements(plugins.IActions)

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

    # IActions

    def get_actions(self):
        return { 'send_email_reminders': action.send_email_reminders }

    
