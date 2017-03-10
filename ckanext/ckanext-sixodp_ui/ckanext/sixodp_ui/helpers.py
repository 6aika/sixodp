import logging
import httplib
import ckan.lib.base as base
from pylons import config
import json
import ckan.lib.i18n as i18n
import ckan.logic as logic
import ckan.model as model
from ckan.model.package import Package
from ckan.lib.dictization.model_dictize import group_list_dictize

from pylons.i18n import gettext
from ckanext.scheming.helpers import lang
from ckan.common import _


NotFound = logic.NotFound
abort = base.abort

log = logging.getLogger(__name__)

# Fetches any CMS content under the configured wp_api_base_url endpoint
def get_wp_api_content(action):
    response_data_dict = {}
    try:
        connection = httplib.HTTPConnection(config.get('ckanext.sixodp_ui.cms_site_url'))
        url = config.get('ckanext.sixodp_ui.wp_api_base_url') + "/" + action
        connection.request("GET", url)
        response_data_dict = json.loads(connection.getresponse().read())
        connection.close()
    except:
        log.error('Connection to WP api failed')

    return response_data_dict

def get_notifications():
    response_data = get_wp_api_content('notification')
    notifications = []

    if (type(response_data) is list):
        for item in response_data:
            notifications.append({
                'title': item['title']['rendered'],
            })

    return notifications

# Serves the same functionality for CMS menus as get_wp_api_content() for all other content
# Needs a separate method since menus are located in a separate enpoint
def get_wordpress_menus():
    connection = httplib.HTTPConnection(config.get('ckanext.sixodp_ui.cms_site_url'))
    connection.request("GET", "/wp-json/wp-api-menus/v2/menus")
    response_data_dict = json.loads(connection.getresponse().read())
    connection.close()

    wp_menus = {}
    for menu in response_data_dict:
        wp_menus[menu['name']] = str(menu['term_id'])
    return wp_menus

def get_navigation_items_by_menu_location(wp_menu_location):
    wp_menus = get_wordpress_menus()

    connection = httplib.HTTPConnection(config.get('ckanext.sixodp_ui.cms_site_url'))
    connection.request("GET", "/wp-json/wp-api-menus/v2/menus/" + wp_menus.get(wp_menu_location))
    response_data_dict = json.loads(connection.getresponse().read())
    connection.close()

    navigation_items = []
    if(response_data_dict.get('items')):
        for item in response_data_dict['items']:
            navigation_items.append({
                'title': item.get('title'),
                'url': item.get('url')
            })

    return navigation_items

def get_main_navigation_items():
    return get_navigation_items_by_menu_location(config.get('ckanext.sixodp_ui.wp_main_menu_location') + '_' + i18n.get_lang())

def get_footer_navigation_items():
    return get_navigation_items_by_menu_location(config.get('ckanext.sixodp_ui.wp_footer_menu_location') + '_' + i18n.get_lang())

def get_groups_for_package(package_id):
    context = {'model': model, 'session': model.Session,
               'for_view': True, 'use_cache': False}

    group_list = []

    try:
        pkg_obj = Package.get(package_id)
        group_list = group_list_dictize(pkg_obj.get_groups('group', None), context)
    except (NotFound):
        abort(404, _('Dataset not found'))

    return group_list


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


def scheming_language_text_or_empty(text, prefer_lang=None):
    """
    :param text: {lang: text} dict or text string
    :param prefer_lang: choose this language version if available

    Convert "language-text" to users' language by looking up
    language in dict or using gettext if not a dict
    """
    if not text:
        return u''

    if hasattr(text, 'get'):
        try:
            if prefer_lang is None:
                prefer_lang = lang()
        except:
            pass  # lang() call will fail when no user language available
        else:
            if prefer_lang in _LOCALE_ALIASES:
                prefer_lang = _LOCALE_ALIASES[prefer_lang]
            try:
                return text[prefer_lang]
            except KeyError:
                return ''

    t = gettext(text)
    if isinstance(t, str):
        return t.decode('utf-8')
    return t


def resource_display_name(resource_dict):
    name = get_translated(resource_dict, 'name')
    if name:
        return name

    else:
        return resource_dict['name']
