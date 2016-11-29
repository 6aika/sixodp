import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
from ckanext.collection import helpers

class CollectionPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.ITemplateHelpers)
    plugins.implements(plugins.IRoutes, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'collection')


    ## ITemplateHelpers

    def get_helpers(self):
        return {
            'check_access': helpers.check_access
        }


    # IRoutes

    def before_map(self, map):
        map.connect('/collection',
                    controller='ckanext.collection.controller:CollectionController',
                    action='search_collection')

        return map