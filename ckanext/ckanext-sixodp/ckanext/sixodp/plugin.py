import json
from datetime import datetime

import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit


import ckan.logic as logic
import random
import urllib
import ckan.lib.i18n as i18n
import logging
import copy
from ckan.common import _
from ckanext.sixodp import helpers
from ckanext.sixodp.logic import action
from ckan.lib.plugins import DefaultTranslation

from .views import sixodp
from .validators import convert_to_list, tag_string_or_tags_required, set_private_if_not_admin, \
    create_fluent_tags, create_tags, lower_if_exists, upper_if_exists, save_to_groups, \
    list_to_string, tag_list_output, repeating_text, repeating_text_output, only_default_lang_required


get_action = logic.get_action
config = toolkit.config

unicode_safe = toolkit.get_validator('unicode_safe')

try:
    from collections import OrderedDict  # 2.7
except ImportError:
    from sqlalchemy.util import OrderedDict

log = logging.getLogger(__name__)


def service_alerts():
    message = config.get('ckanext.sixodp.service_alert.message')
    category = "info"
    if message:
        return [{"message": message, "category": category}]
    else:
        return []


def get_recent_content():

    search_dict = {
        'sort': 'metadata_created desc',
        'rows': 5
    }

    items = logic.get_action('package_search')({}, search_dict)

    result = []
    for item in items['results']:
        result.append({
            'title': item['title'],
            'metadata_created': item['metadata_created'],
            'href': '/' + item['type'] + '/' + item['name']
        })

    return result


def get_popular_tags():

    search_dict = {
        'facet.field': ['tags'],
        'facet.limit': 5,
        'rows': 0
    }

    items = logic.get_action('package_search')({}, search_dict)

    return items['facets']['tags']


def get_categories():

    search_dict = {
        'all_fields': True
    }

    items = logic.get_action('group_list')({}, search_dict)

    return items


def get_homepage_organizations(count=1):
    def get_group(id):
        context = {'ignore_auth': True,
                   'limits': {'packages': 2},
                   'for_view': True}
        data_dict = {'id': id,
                     'include_datasets': True}

        try:
            out = logic.get_action('organization_show')(context, data_dict)
        except logic.NotFound:
            return None
        return out

    def get_configured_groups(configured_count):
        ''' Return list of valid groups in ckan.featured_orgs up to configured_count or an empty list if none present '''
        items = config.get('ckan.featured_orgs', '').split()
        result = []

        for group_name in items:
            group = get_group(group_name)
            if not group:
                log.warning('Setting ckan.featured_orgs: Organisation \'' + group_name + '\' not found')
                continue
            result.append(group)

        if len(result) > configured_count:
            result = result[:configured_count]

        return result

    def get_random_groups(random_count, excluded_ids):
        ''' Return random valid groups up to random_count where id is not in excluded '''
        result = []
        found = []

        items = logic.get_action('organization_list')({}, {})

        for group_name in items:
            group = get_group(group_name)

            if not group:
                continue
            if group['id'] in excluded_ids:
                continue
            # check if duplicate
            if group['id'] in found:
                continue
            # skip orgs with 0 packages
            if group['package_count'] is 0:
                continue
            # skip orgs with 1 package, if shared resource is "no"
            if group['package_count'] is 1 and group['packages'][0].get('shared_resource', 'no') == 'no':
                continue

            found.append(group['id'])
            result.append(group)

        if len(result) > random_count:
            result = random.sample(result, random_count)

        return result

    groups = get_configured_groups(count)

    # If result comes short, fill it in with random groups
    if len(groups) < count:
        groups = groups + get_random_groups(count - len(groups), [x['id'] for x in groups])

    return groups


def unquote_url(url):
    return urllib.unquote(url)


def get_qa_openness(dataset):
    qa = dataset.get('qa')
    if not qa or not isinstance(qa, dict):
        extra_vars = {
            'openness_score': None
        }
    else:
        extra_vars = copy.deepcopy(qa)
    return toolkit.literal(
        toolkit.render('qa/openness_stars_brief.html',
                  extra_vars=extra_vars))


def admin_only(context, data_dict=None):
    return {'success': False, 'msg': 'Access restricted to system administrators'}

class SixodpPlugin(plugins.SingletonPlugin, DefaultTranslation):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IConfigurable)
    plugins.implements(plugins.interfaces.IFacets, inherit=True)
    plugins.implements(plugins.ITemplateHelpers)
    if toolkit.check_ckan_version(min_version='2.5.0'):
        plugins.implements(plugins.ITranslation, inherit=True)
    plugins.implements(plugins.IPackageController, inherit=True)
    plugins.implements(plugins.IActions, inherit=True)
    plugins.implements(plugins.IAuthFunctions)
    plugins.implements(plugins.IBlueprint)
    plugins.implements(plugins.IValidators)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'sixodp')

    def update_config_schema(self, schema):
        ignore_missing = toolkit.get_validator('ignore_missing')

        schema.update({
            'ckanext.sixodp.service_alert.message': [ignore_missing, unicode_safe],
        })

        return schema

    # IConfigurable

    def configure(self, config):
        # Raise an exception if required configs are missing
        required_keys = [
            'ckanext.sixodp.cms_site_url',
            'ckanext.sixodp.wp_main_menu_location',
            'ckanext.sixodp.wp_footer_menu_location',
            'ckanext.sixodp.wp_social_menu_location',
        ]

        for key in required_keys:
            if config.get(key) is None:
                raise RuntimeError(
                    'Required configuration option {0} not found.'.format(
                        key
                    )
                )

    # IFacets #

    def dataset_facets(self, facets_dict, package_type):
        if package_type == 'dataset':
            facets_dict = OrderedDict()
            facets_dict.update({'res_format': _('Formats')})
            facets_dict.update({'vocab_geographical_coverage': _('Geographical Coverage')})
            facets_dict.update({'groups': _('Groups')})
            facets_dict.update({'organization': _('Organizations')})
            facets_dict.update({'collections': _('Collections')})

        return facets_dict

    def organization_facets(self, facets_dict, organization_type, package_type):
        if organization_type == 'organization':
            facets_dict = OrderedDict()
            facets_dict.update({'res_format': _('Formats')})
            facets_dict.update({'vocab_geographical_coverage': _('Geographical Coverage')})
            facets_dict.update({'groups': _('Groups')})
            facets_dict.update({'organization': _('Organizations')})
            facets_dict.update({'collections': _('Collections')})
        return facets_dict

    def group_facets(self, facets_dict, group_type, package_type):
        if group_type == 'group':
            facets_dict = OrderedDict()
            facets_dict.update({'res_format': _('Formats')})
            facets_dict.update({'vocab_geographical_coverage': _('Geographical Coverage')})
            facets_dict.update({'groups': _('Groups')})
            facets_dict.update({'organization': _('Organizations')})
            facets_dict.update({'collections': _('Collections')})
        return facets_dict

    def get_helpers(self):
        return {
            'get_recent_content': get_recent_content,
            'get_popular_tags': get_popular_tags,
            'get_categories': get_categories,
            'get_homepage_organizations': get_homepage_organizations,
            'service_alerts': service_alerts,
            'unquote_url': unquote_url,
            'get_translated': helpers.get_translated,
            'get_current_lang': helpers.get_current_lang,
            'get_qa_openness': get_qa_openness,
            'dataset_display_name': helpers.dataset_display_name,
            'get_navigation_slug': helpers.get_navigation_slug,
            'get_navigation_items': helpers.get_navigation_items,
            'get_navigation_items_by_menu_location': helpers.get_navigation_items_by_menu_location,
            'get_footer_navigation_items': helpers.get_footer_navigation_items,
            'get_social_links': helpers.get_social_links,
            'get_social_link_icon_class': helpers.get_social_link_icon_class,
            'get_package_groups': helpers.get_package_groups,
            'scheming_language_text_or_empty': helpers.scheming_language_text_or_empty,
            'resource_display_name': helpers.resource_display_name,
            'get_notifications': helpers.get_notifications,
            'menu_is_active': helpers.menu_is_active,
            'build_nav_main': helpers.build_nav_main,
            'get_search_tags': helpers.get_search_tags,
            'get_all_groups': helpers.get_all_groups,
            'get_single_group': helpers.get_single_group,
            'get_created_or_updated': helpers.get_created_or_updated,
            'get_cookiehub_domain_code': helpers.get_cookiehub_domain_code,
            'call_toolkit_function': helpers.call_toolkit_function,
            'add_locale_to_source': helpers.add_locale_to_source,
            'get_lang': helpers.get_current_lang,
            'get_lang_prefix': helpers.get_lang_prefix,
            'scheming_field_only_default_required': helpers.scheming_field_only_default_required,
            'get_current_date_formatted': helpers.get_current_date_formatted,
            'get_package_groups_by_type': helpers.get_package_groups_by_type,
            'get_translated_or_default_locale': helpers.get_translated_or_default_locale,
            'show_qa': helpers.show_qa,
            'scheming_category_list': helpers.scheming_category_list,
            'check_group_selected': helpers.check_group_selected,
            'get_field_from_schema': helpers.get_field_from_schema
        }

    def before_dataset_search(self, search_params):
        '''Initializes default sorting if sorting is missing, replaces metadata_created with custom date_released.'''
        sort = search_params.get('sort', '')
        if len(sort) == 0:
            sort = u'date_released desc'
            search_params.update({'sort': sort})
        elif 'metadata_created' in sort:
            sort = sort.replace('metadata_created', 'date_released')
            search_params.update({'sort': sort})

        return search_params

    def after_dataset_search(self, search_results, search_params):
        if(search_results['search_facets'].get('groups')):
            context = {'for_view': True, 'with_private': False}
            data_dict = {
                'all_fields': True,
                'include_extras': True,
                'type': 'group',
            }
            groups_with_extras = get_action('group_list')(context, data_dict)

            for i, facet in enumerate(search_results['search_facets']['groups'].get('items', [])):
                for group in groups_with_extras:
                    if facet['name'] == group['name']:
                        search_results['search_facets']['groups']['items'][i]['title_translated'] = group.get('title_translated')
        return search_results

    def before_dataset_index(self, data_dict):

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
            data_dict['date_updated'] = data_dict['date_released']
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

        update_frequency = data_dict.get('update_frequency')
        if update_frequency:
            update_frequency_json = json.loads(update_frequency)
            if update_frequency_json.get('fi'):
                data_dict['vocab_update_frequency_fi'] = [tag for tag in update_frequency_json['fi']]
            if update_frequency_json.get('sv'):
                data_dict['vocab_update_frequency_sv'] = [tag for tag in update_frequency_json['sv']]
            if update_frequency_json.get('en'):
                data_dict['vocab_update_frequency_en'] = [tag for tag in update_frequency_json['en']]

        return data_dict

    # This function requires overriding resource_create and resource_update by adding keep_deletable_attributes_in_api to context
    def after_dataset_show(self, context, data_dict):

        keep_deletable_attributes_in_api = config.get('ckanext.sixodp.keep_deletable_attributes_in_api',
                                                      context.get('keep_deletable_attributes_in_api', False))

        if keep_deletable_attributes_in_api is False and context.get('for_edit') is not True:
            if data_dict.get('search_synonyms', None) is not None:
                data_dict.pop('search_synonyms')

        return data_dict
    # IActions

    def get_actions(self):
        return {
            'package_autocomplete': action.package_autocomplete,
            'resource_create': action.resource_create,
            'resource_update': action.resource_update,
            'resource_delete': action.resource_delete,
            'package_resource_reorder': action.package_resource_reorder,
            'package_patch': action.package_patch,
            'user_create': action.user_create,
            'group_create': action.group_create
        }

    # IAuthFunctions

    def get_auth_functions(self):
        return {'user_list': admin_only,
                'revision_list': admin_only,
                'revision_diff': admin_only,
                'package_revision_list': admin_only
                }

    # IBlueprint
    def get_blueprint(self):
        return [sixodp]

    # IValidators
    def get_validators(self):
        return {
            'lower_if_exists': lower_if_exists,
            'upper_if_exists': upper_if_exists,
            'tag_string_or_tags_required': tag_string_or_tags_required,
            'create_tags': create_tags,
            'create_fluent_tags': create_fluent_tags,
            'set_private_if_not_admin': set_private_if_not_admin,
            'list_to_string': list_to_string,
            'convert_to_list': convert_to_list,
            'tag_list_output': tag_list_output,
            'repeating_text': repeating_text,
            'repeating_text_output': repeating_text_output,
            'only_default_lang_required': only_default_lang_required,
            'save_to_groups': save_to_groups
        }


@toolkit.chained_action
def package_search(original_action, context, data_dict):

    if data_dict.get('q') and not data_dict.get('sort'):
        data_dict['sort'] = 'score desc, metadata_modified desc'
    elif not data_dict.get('sort'):
        data_dict['sort'] = 'metadata_created desc'

    return original_action(context, data_dict)