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

try:
    from collections import OrderedDict  # 2.7
except ImportError:
    from sqlalchemy.util import OrderedDict

log = logging.getLogger(__name__)

def ensure_translated(s):
    ts = type(s)
    if ts == unicode:
        return s
    elif ts == str:
        return unicode(s)
    elif ts == dict:
        language = i18n.get_lang()
        return ensure_translated(s.get(language, u""))


_LOCALE_ALIASES = {'en_GB': 'en'}

def get_translated(data_dict, field):
    language = i18n.get_lang()
    if language in _LOCALE_ALIASES:
        language = _LOCALE_ALIASES[language]

    try:
        return data_dict[field+'_translated'][language]
    except KeyError:
        return data_dict.get(field, '')

# Copied from core ckan to call over ridden get_translated
def dataset_display_name(package_or_package_dict):
    if isinstance(package_or_package_dict, dict):
        return get_translated(package_or_package_dict, 'title') or \
               package_or_package_dict['name']
    else:
        # FIXME: we probably shouldn't use the same functions for
        # package dicts and real package objects
        return package_or_package_dict.title or package_or_package_dict.name




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


class Sixodp_UiPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.interfaces.IFacets, inherit=True)
    plugins.implements(plugins.ITemplateHelpers)

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

    # IFacets #

    def dataset_facets(self, facets_dict, package_type):
        facets_dict = OrderedDict()
        facets_dict.update({'res_format': _('Formats')})
        facets_dict.update({'organization': _('Organization')})
        facets_dict.update({'groups': _('Groups')})
        facets_dict.update({'maintainer': _('Maintainer')})

        return facets_dict

    def get_helpers(self):
        return {'get_recent_content': get_recent_content,
                'get_popular_tags': get_popular_tags,
                'get_categories': get_categories,
                'get_homepage_organizations': get_homepage_organizations,
                'service_alerts': service_alerts,
                'unquote_url': unquote_url,
                'ensure_translated': ensure_translated,
                'get_translated': get_translated,
                'get_qa_openness': get_qa_openness,
                'dataset_display_name': dataset_display_name,
                'get_main_navigation_items': helpers.get_main_navigation_items
                }
