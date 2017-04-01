import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit

from ckanext.spatial.interfaces import ISpatialHarvester
import logging

log = logging.getLogger(__name__)



class Sixodp_HarvesterPlugin(plugins.SingletonPlugin):
    plugins.implements(ISpatialHarvester, inherit=True)

    def get_package_dict(self, context, data_dict):

        package_dict = data_dict['package_dict']

        tag_list = [tag['name'] for tag in package_dict['tags']]
        package_dict['date_released'] = "2017-03-20"
        package_dict['date_updated'] = "2017-03-20"
        package_dict['license_id'] = "CC-BY-4.0"
        package_dict['geographical_coverage'] = "tampere"
        package_dict['keywords'] = {"fi": tag_list }

        package_dict.pop('tags')


        return package_dict

