import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
from ckan.lib.plugins import DefaultTranslation
from ckanext.sixodp_showcasesubmit import helpers
from ckanext.sixodp_showcasesubmit.views import showcasesubmitter

unicode_safe = toolkit.get_validator('unicode_safe')

class Sixodp_ShowcasesubmitPlugin(plugins.SingletonPlugin, DefaultTranslation):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IConfigurable)
    plugins.implements(plugins.ITemplateHelpers)
    if toolkit.check_ckan_version(min_version='2.5.0'):
        plugins.implements(plugins.ITranslation, inherit=True)
    plugins.implements(plugins.IBlueprint)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'sixodp_showcasesubmit')

    def update_config_schema(self, schema):
        ignore_missing = toolkit.get_validator('ignore_missing')

        schema.update({
            'ckanext.sixodp_showcasesubmit.recipient_emails': [ignore_missing, unicode_safe],
        })

        return schema

    # IConfigurable

    def configure(self, config):
        # Raise an exception if required configs are missing
        required_keys = (
            'ckanext.sixodp_showcasesubmit.creating_user_username',
            'ckanext.sixodp_showcasesubmit.recaptcha_sitekey',
            'ckanext.sixodp_showcasesubmit.recaptcha_secret',
            'ckanext.sixodp_showcasesubmit.recipient_emails'
        )

        for key in required_keys:
            if config.get(key) is None:
                raise RuntimeError(
                    'Required configuration option {0} not found.'.format(
                        key
                    )
                )

    # IBlueprint

    def get_blueprint(self):
        return [showcasesubmitter]

    # ITemplateHelpers

    def get_helpers(self):
        return {'get_showcasesubmit_recaptcha_sitekey': helpers.get_showcasesubmit_recaptcha_sitekey}