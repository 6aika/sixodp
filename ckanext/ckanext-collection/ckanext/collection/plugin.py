import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
from routes.mapper import SubMapper

from ckanext.collection.logic import action

class CollectionPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IRoutes, inherit=True)
    plugins.implements(plugins.IActions)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'collection')

    # IRoutes

    def before_map(self, map):
        with SubMapper(map, controller='ckanext.collection.controller:CollectionController') as m:
            m.connect('/collection', action='search_collection')

            m.connect('/collection/new', action='new')

            m.connect('/collection/:id', action='read')

            m.connect('dataset_collection_list', '/dataset/collections/{id}',
                      action='dataset_collection_list', ckan_icon='picture')

        map.redirect('/collections', '/collection')

        return map

    # IActions

    def get_actions(self):
        action_functions = {
            'ckanext_collection_package_collections_list':
                action.package_collections_list
        }
        return action_functions
