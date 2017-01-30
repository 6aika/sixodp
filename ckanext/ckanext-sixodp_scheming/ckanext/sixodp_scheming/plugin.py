import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
import validators
from datetime import datetime
import json
import helpers

from ckan.logic import NotFound
import logging

log = logging.getLogger(__name__ )


def create_vocabulary(name):
    user = toolkit.get_action('get_site_user')({'ignore_auth': True}, {})
    context = {'user': user['name']}

    try:
        data = {'id': name}
        v = toolkit.get_action('vocabulary_show')(context, data)
        log.info( name + " vocabulary already exists, skipping.")
    except NotFound:
        log.info("Creating vocab '" + name + "'")
        data = {'name': name}
        v = toolkit.get_action('vocabulary_create')(context, data)

    return v

def create_tag_to_vocabulary(tag, vocab):
    user = toolkit.get_action('get_site_user')({'ignore_auth': True}, {})
    context = {'user': user['name']}

    try:
        data = {'id': vocab}
        v = toolkit.get_action('vocabulary_show')(context, data)

    except NotFound:
        log.info("Creating vocab '" + vocab + "'")
        data = {'name': vocab}
        v = toolkit.get_action('vocabulary_create')(context, data)

    data = {
        "name": tag,
        "vocabulary_id": v['id']}

    toolkit.get_action('tag_create')(context, data)


class Sixodp_SchemingPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IValidators)
    plugins.implements(plugins.IPackageController, inherit=True)
    plugins.implements(plugins.ITemplateHelpers)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'sixodp_scheming')

    def get_validators(self):
        return {
            'lower_if_exists': validators.lower_if_exists,
            'upper_if_exists': validators.upper_if_exists,
            'tag_string_or_tags_required': validators.tag_string_or_tags_required,
            'create_tags': validators.create_tags,
            'set_private_if_not_admin': validators.set_private_if_not_admin,
            'list_to_string': validators.list_to_string,
            'convert_to_list': validators.convert_to_list,
            'tag_list_output': validators.tag_list_output
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


        if data_dict.get('geographical_coverage'):
            data_dict['vocab_geographical_coverage'] = [tag for tag in json.loads(data_dict['geographical_coverage'])]


        return data_dict





    def get_helpers(self):
        return {'call_toolkit_function': helpers.call_toolkit_function,
                'add_locale_to_source': helpers.add_locale_to_source,
                'get_lang': helpers.get_current_lang}