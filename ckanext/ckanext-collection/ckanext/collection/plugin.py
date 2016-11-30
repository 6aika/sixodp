import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit

class CollectionPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IRoutes, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'collection')

    # IRoutes

    def before_map(self, map):
        map.connect('/collection',
                    controller='ckanext.collection.controller:CollectionController',
                    action='search_collection')

        map.connect('/collection/new',
                    controller='ckanext.collection.controller:CollectionController',
                    action='new')

        map.connect('/collection/:id',
                    controller='ckanext.collection.controller:CollectionController',
                    action='read')

        return map