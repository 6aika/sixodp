import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit


class EditorPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IRoutes, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        # toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'editor')
        toolkit.add_resource('public/js/', 'editor_js')

    # IRoutes

    def before_map(self, map):
        map.connect('/editor',
                    controller='ckanext.editor.controller:EditorController',
                    action='package_search')

        map.connect('/editor/package_update',
                    controller='ckanext.editor.controller:EditorController',
                    action='package_update')

        return map