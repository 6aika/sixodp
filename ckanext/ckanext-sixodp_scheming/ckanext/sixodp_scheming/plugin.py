import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
import validators
from datetime import datetime
import json
import helpers

from ckan.logic import NotFound
from ckan.lib.plugins import DefaultTranslation
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

    context['defer_commit'] = True
    toolkit.get_action('tag_create')(context, data)


class Sixodp_SchemingPlugin(plugins.SingletonPlugin, DefaultTranslation):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IValidators)
    plugins.implements(plugins.IPackageController, inherit=True)
    plugins.implements(plugins.ITemplateHelpers)
    if toolkit.check_ckan_version(min_version='2.5.0'):
        plugins.implements(plugins.ITranslation, inherit=True)

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
            'create_fluent_tags': validators.create_fluent_tags,
            'set_private_if_not_admin': validators.set_private_if_not_admin,
            'list_to_string': validators.list_to_string,
            'convert_to_list': validators.convert_to_list,
            'tag_list_output': validators.tag_list_output,
            'repeating_text': validators.repeating_text,
            'repeating_text_output': validators.repeating_text_output,
            'only_default_lang_required': validators.only_default_lang_required
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

        keywords = data_dict.get('keywords')
        if keywords:
            keywords_json = json.loads(keywords)
            if keywords_json.get('fi'):
                data_dict['vocab_keywords_fi'] = [tag for tag in keywords_json['fi']]
            if keywords_json.get('sv'):
                data_dict['vocab_keywords_sv'] = [tag for tag in keywords_json['sv']]
            if keywords_json.get('en'):
                data_dict['vocab_keywords_en'] = [tag for tag in keywords_json['en']]

        return data_dict


    def after_show(self, context, data_dict):
        if context.get('for_edit') is not True:
            if data_dict.get('search_synonyms', None) is not None:
                data_dict.pop('search_synonyms')



        return data_dict


    def get_helpers(self):
        return {'call_toolkit_function': helpers.call_toolkit_function,
                'add_locale_to_source': helpers.add_locale_to_source,
                'get_lang': helpers.get_current_lang,
                'get_lang_prefix': helpers.get_lang_prefix,
                'scheming_field_only_default_required': helpers.scheming_field_only_default_required,
                'get_current_date': helpers.get_current_date,
                'get_package_groups_by_type': helpers.get_package_groups_by_type,
                'get_translated_or_default_locale': helpers.get_translated_or_default_locale}