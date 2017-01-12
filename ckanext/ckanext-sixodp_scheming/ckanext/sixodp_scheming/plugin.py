import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
import validators
from datetime import datetime

class Sixodp_SchemingPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IValidators)
    plugins.implements(plugins.IPackageController, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'sixodp_scheming')

    def get_validators(self):
        return {
            'lower_if_exists': validators.lower_if_exists,
            'upper_if_exists': validators.upper_if_exists,
            'tag_string_or_tags_required': validators.tag_string_or_tags_required
            }

    # IPackageController

    def before_index(self, data_dict):

        if data_dict.get('date_released', None) is None:
            data_dict['date_released'] = data_dict['metadata_created']
        else:
            date_str = data_dict['date_released']
            try:
                datetime.strptime(date_str, "%Y-%m-%dT%H:%M:%SZ")
            except ValueError:
                d = datetime.strptime(date_str, "%Y-%m-%d")
                d = datetime.combine(d, datetime.min.time()).replace(tzinfo=None).isoformat() + 'Z'
                data_dict['date_released'] = d
            #d = datetime.strptime(date_str,)

        if data_dict.get('date_updated', None) is None:
            data_dict['date_updated'] = data_dict['metadata_modified']
        else:
            date_str = data_dict['date_updated']
            try:
                datetime.strptime(date_str, "%Y-%m-%dT%H:%M:%SZ")
            except ValueError:
                d = datetime.strptime(date_str, "%Y-%m-%d")
                d = datetime.combine(d, datetime.min.time()).replace(tzinfo=None).isoformat() + 'Z'
                data_dict['date_updated'] = d

        return data_dict