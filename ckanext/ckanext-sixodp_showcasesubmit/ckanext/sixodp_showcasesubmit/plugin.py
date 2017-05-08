import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
from ckan.lib.plugins import DefaultTranslation
from ckanext.sixodp_showcasesubmit import helpers

class Sixodp_ShowcasesubmitPlugin(plugins.SingletonPlugin, DefaultTranslation):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IConfigurable)
    plugins.implements(plugins.IRoutes, inherit=True)
    plugins.implements(plugins.ITemplateHelpers)
    if toolkit.check_ckan_version(min_version='2.5.0'):
        plugins.implements(plugins.ITranslation, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'sixodp_showcasesubmit')

    # IConfigurable

    def configure(self, config):
        # Raise an exception if required configs are missing
        required_keys = (
            'ckanext.sixodp_showcasesubmit.creating_user_username',
            'ckanext.sixodp_showcasesubmit.recaptcha_sitekey',
            'ckanext.sixodp_showcasesubmit.recaptcha_secret',
        )

        for key in required_keys:
            if config.get(key) is None:
                raise RuntimeError(
                    'Required configuration option {0} not found.'.format(
                        key
                    )
                )

    # IRoutes

    def before_map(self, map):
        map.connect('/submit-showcase',
                    controller='ckanext.sixodp_showcasesubmit.controller:Sixodp_ShowcasesubmitController',
                    action='index',
                    conditions=dict(method=['GET']))

        map.connect('/submit-showcase',
                    controller='ckanext.sixodp_showcasesubmit.controller:Sixodp_ShowcasesubmitController',
                    action='submit',
                    conditions=dict(method=['POST']))

        return map

    # ITemplateHelpers

    def get_helpers(self):
        return {'get_recaptcha_sitekey': helpers.get_recaptcha_sitekey}