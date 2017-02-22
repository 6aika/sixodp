import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
from ckan.lib.plugins import DefaultTranslation


class DatasubmitterPlugin(plugins.SingletonPlugin, DefaultTranslation):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IConfigurable)
    plugins.implements(plugins.IRoutes, inherit=True)
    if toolkit.check_ckan_version(min_version='2.5.0'):
        plugins.implements(plugins.ITranslation, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'datasubmitter')

    # IConfigurable

    def configure(self, config):
        # Raise an exception if required configs are missing
        required_keys = (
            'ckanext.datasubmitter.creating_user_username',
            'ckanext.datasubmitter.organization_name_or_id'
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
        map.connect('/submit-data',
                    controller='ckanext.datasubmitter.controller:DatasubmitterController',
                    action='index',
                    conditions=dict(method=['GET']))

        map.connect('/submit-data',
                    controller='ckanext.datasubmitter.controller:DatasubmitterController',
                    action='submit',
                    conditions=dict(method=['POST']))

        map.connect('/submit-data/ajax-submit',
                    controller='ckanext.datasubmitter.controller:DatasubmitterController',
                    action='ajax_submit')

        return map