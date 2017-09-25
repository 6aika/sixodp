import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
import pylons.config as config

import ckan.logic as logic
import random
import urllib
import ckan.lib.i18n as i18n
import logging
import copy
from ckan.common import _
from ckanext.sixodp_ui import helpers
from ckanext.sixodp_ui.logic import action
from ckan.lib.plugins import DefaultTranslation

get_action = logic.get_action

try:
    from collections import OrderedDict  # 2.7
except ImportError:
    from sqlalchemy.util import OrderedDict

log = logging.getLogger(__name__)


def service_alerts():
    message = config.get('ckanext.sixodp_ui.service_alert.message')
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


class Sixodp_UiPlugin(plugins.SingletonPlugin, DefaultTranslation):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IConfigurable)
    plugins.implements(plugins.interfaces.IFacets, inherit=True)
    plugins.implements(plugins.ITemplateHelpers)
    if toolkit.check_ckan_version(min_version='2.5.0'):
        plugins.implements(plugins.ITranslation, inherit=True)
    plugins.implements(plugins.IPackageController, inherit=True)
    plugins.implements(plugins.IActions, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_public_directory(config_, 'public')
        toolkit.add_resource('fanstatic', 'sixodp_ui')

    def update_config_schema(self, schema):
        ignore_missing = toolkit.get_validator('ignore_missing')

        schema.update({
            'ckanext.sixodp_ui.service_alert.message': [ignore_missing, unicode],
        })

        return schema

    # IConfigurable

    def configure(self, config):
        # Raise an exception if required configs are missing
        required_keys = [
            'ckanext.sixodp_ui.cms_site_url',
            'ckanext.sixodp_ui.wp_main_menu_location',
            'ckanext.sixodp_ui.wp_footer_menu_location',
            'ckanext.sixodp_ui.wp_social_menu_location',
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
        if(package_type == 'dataset'):
            facets_dict = OrderedDict()
            facets_dict.update({'res_format': _('Formats')})
            facets_dict.update({'vocab_geographical_coverage': _('Geographical Coverage')})
            facets_dict.update({'groups': _('Groups')})
            facets_dict.update({'maintainer': _('Maintainer')})
            facets_dict.update({'collections': _('Collections')})

        return facets_dict

    def get_helpers(self):
        return {'get_recent_content': get_recent_content,
                'get_popular_tags': get_popular_tags,
                'get_categories': get_categories,
                'get_homepage_organizations': get_homepage_organizations,
                'service_alerts': service_alerts,
                'unquote_url': unquote_url,
                'get_translated': helpers.get_translated,
                'get_current_lang': helpers.get_current_lang,
                'get_qa_openness': get_qa_openness,
                'dataset_display_name': helpers.dataset_display_name,
                'get_navigation_items_by_menu_location': helpers.get_navigation_items_by_menu_location,
                'get_footer_navigation_items': helpers.get_footer_navigation_items,
                'get_social_links': helpers.get_social_links,
                'get_social_link_icon_class': helpers.get_social_link_icon_class,
                'get_package_groups': helpers.get_package_groups,
                'scheming_language_text_or_empty': helpers.scheming_language_text_or_empty,
                'resource_display_name': helpers.resource_display_name,
                'get_notifications': helpers.get_notifications,
                'menu_is_active': helpers.menu_is_active,
                'build_nav_main': helpers.build_nav_main
                }

    def before_search(self, search_params):
        '''Initializes default sorting if sorting is missing, replaces metadata_created with custom date_released.'''
        sort = search_params.get('sort', '')
        if len(sort) == 0:
            sort = u'date_released desc'
            search_params.update({'sort': sort})
        elif 'metadata_created' in sort:
            sort = sort.replace('metadata_created', 'date_released')
            search_params.update({'sort': sort})

        return search_params

    def after_search(self, search_results, search_params):

        if(search_results['search_facets'].get('groups')):
            groups_with_extras = []
            for result in search_results['results']:
                for group in result.get('groups', []):
                    context = {'for_view': True, 'with_private': False}

                    data_dict = {
                        'all_fields': True,
                        'include_extras': True,
                        'type': 'group',
                        'id': group['name']
                    }
                    groups_with_extras.append(get_action('group_show')(context, data_dict))

            for i, facet in enumerate(search_results['search_facets']['groups'].get('items', [])):
                for group in groups_with_extras:
                    if facet['name'] == group['name']:
                        search_results['search_facets']['groups']['items'][i]['title_translated'] = group.get('title_translated')

        return search_results

        # IActions

    def get_actions(self):
        return {
            'package_autocomplete': action.package_autocomplete,
        }