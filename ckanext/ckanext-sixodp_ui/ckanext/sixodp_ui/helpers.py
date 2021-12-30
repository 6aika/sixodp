import logging
import httplib
import ckan.lib.base as base
from pylons import config
import json
import ckan.lib.i18n as i18n
import ckan.logic as logic
import ckan.model as model
import ckan.plugins.toolkit as tk
from ckan.model.package import Package
from ckan.lib.dictization.model_dictize import group_list_dictize

from pylons.i18n import gettext
from ckanext.scheming.helpers import lang
from ckan.common import _, c
from webhelpers.html import literal
from ckan.logic import get_action
import ckan.lib.helpers as helpers


NotFound = logic.NotFound
abort = base.abort
request = tk.request

log = logging.getLogger(__name__)

# Fetches any CMS content under the configured wp_api_base_url endpoint
def get_wp_api_content(endpoint, action):
    response_data_dict = {}
    try:
        connection = httplib.HTTPConnection(config.get('ckanext.sixodp_ui.cms_site_url'))
        url = endpoint + "/" + action
        connection.request("GET", url)
        response_data_dict = json.loads(connection.getresponse().read())
        connection.close()
    except:
        log.error('Connection to WP api failed')

    return response_data_dict


def get_notifications():
    notifications_endpoint = config.get('ckanext.sixodp_ui.wp_api_base_url')
    response_data = get_wp_api_content(notifications_endpoint, 'notification')
    notifications = []

    if (type(response_data) is list):
        for item in response_data:
            notifications.append({
                'title': item['title']['rendered'],
            })

    return notifications


def get_navigation_items_by_menu_location(wp_menu_location, localized):
    menu_endpoint = config.get('ckanext.sixodp_ui.wp_api_menus_base_url')

    if (localized == True):
        response_data = get_wp_api_content(menu_endpoint, 'menus/' + wp_menu_location + '_' + i18n.get_lang())
    else:
        response_data = get_wp_api_content(menu_endpoint, 'menus/' + wp_menu_location)

    navigation_items = []
    if(response_data and response_data.get('items')):
        for item in response_data['items']:
            navigation_items.append({
                'title': item.get('title'),
                'url': item.get('url'),
                'children': item.get('children')
            })

    return navigation_items


def get_footer_navigation_items():
    return get_navigation_items_by_menu_location(config.get('ckanext.sixodp_ui.wp_footer_menu_location'), True)


def get_social_links():
    return get_navigation_items_by_menu_location(config.get('ckanext.sixodp_ui.wp_social_menu_location'), False)


def get_social_link_icon_class(item):
    title = item.get('title').lower()
    if title == 'facebook':
        return 'fa fa-facebook-square'
    elif title == 'twitter':
        return 'fa fa-twitter-square'
    elif title == 'youtube':
        return 'fa fa-youtube-square'
    elif title == 'rss':
        return 'fa fa-rss-square'
    elif title == 'tumblr':
        return 'fa fa-tumblr-square'
    elif title == 'github':
        return 'fa fa-github-square'
    elif title == 'instagram':
        return 'fa fa-instagram'
    elif title == 'linkedin':
        return 'fa fa-linkedin-square'
    elif title == 'flickr':
        return 'fa fa-flickr'
    elif title == 'slideshare':
        return 'fa fa-slideshare'
    elif title == 'newsletter':
        return 'fa fa-news-o'
    elif title == 'speakerdeck':
        return 'fa fa-caret-square-o-right'
    else:
        return 'icon-external-link-sign'


def menu_is_active(menu_url, current_path):
    return current_path in menu_url


def get_all_groups():
    context = {'model': model, 'session': model.Session,
               'for_view': True, 'use_cache': False}

    data_dict = {
        'all_fields': True,
        'include_extras': True
    }

    groups = get_action('group_list')(context, data_dict)

    return groups

def get_single_group(group_dict, groups):

    return next(group for group in groups if group.get('name') == group_dict.get('name'))



# This is not the most efficient way of listing package groups that include all group schema fields, however
# at this point the only way without major CKAN core changes
def get_package_groups(package_id):
    context = {'model': model, 'session': model.Session,
               'for_view': True, 'use_cache': False}

    data_dict = {
        'all_fields': True,
        'include_extras': True
    }

    groups = get_action('group_list')(context, data_dict)
    group_list = []

    try:
        pkg_obj = Package.get(package_id)
        pkg_group_ids = set(group['id'] for group
                        in group_list_dictize(pkg_obj.get_groups('group', None), context))

        group_list = [group
                     for group in groups if
                     group['id'] in pkg_group_ids]

        if c.user:
            context = {'model': model, 'session': model.Session,
                       'user': c.user, 'for_view': True,
                       'auth_user_obj': c.userobj, 'use_cache': False,
                       'is_member': True}

            data_dict = {'id': package_id}
            users_groups = get_action('group_list_authz')(context, data_dict)

            user_group_ids = set(group['id'] for group
                                 in users_groups)

            for group in group_list:
                group['user_member'] = (group['id'] in user_group_ids)

    except (NotFound):
        abort(404, _('Dataset not found'))

    return group_list


_LOCALE_ALIASES = {'en_GB': 'en'}

def get_translated(data_dict, field, fallback_language=None):
    if(data_dict.get(field+'_translated')):
        language = i18n.get_lang()
        if language in _LOCALE_ALIASES:
            language = _LOCALE_ALIASES[language]

        try:
            return data_dict[field+'_translated'][language] or data_dict[field+'_translated'][fallback_language]
        except KeyError:
            return data_dict.get(field, '')
    if data_dict.get('display_name', None) is not None:
        return data_dict.get('display_name')
    return data_dict.get(field)


def get_current_lang():
    return i18n.get_lang()


# Copied from core ckan to call over ridden get_translated
def dataset_display_name(package_or_package_dict):
    if isinstance(package_or_package_dict, dict):
        return get_translated(package_or_package_dict, 'title', 'fi') or \
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


def check_if_active(parent_menu, menu):
    current_url = helpers.full_current_url().replace('https', '').replace('http', '')
    menu_url = menu.get('url').replace('https', '').replace('http', '')

    # Set menu to active if the menu url matches the full current page url
    active = current_url == menu_url

    # Check menus that have no parent in the WP menu api structure, usually a CKAN submenu which has not been
    # configured through WordPress
    if active == False and parent_menu is None:
        active = menu_url in current_url

    return active


def build_nav_main():
    navigation_tree = get_navigation_items_by_menu_location(config.get('ckanext.sixodp_ui.wp_main_menu_location'), True)

    def construct_menu_tree(menu, is_submenu = False):
        active = check_if_active(None, menu)
        children = ''

        if menu.get('children'):
            for child_item in menu.get('children'):
                # If WP menu has links to non-existing pages
                if child_item.get('url') is False:
                    continue
                # Parent will be set as active if any of its children is active
                if check_if_active(menu, child_item):
                    active = True

                children += construct_menu_tree(child_item, True)

        if len(children) > 0:
            subnav_toggle = literal('<button class="subnav-toggle"><span class="sr-only">' + _('Show submenu for ') + menu.get('title') + '</span><i class="fa fa-chevron-down"></i></button>')
            subnav = literal('<ul class="nav navbar-nav subnav">') + children + literal('</ul>')
            return make_menu_item(menu, active, is_submenu) + subnav_toggle + subnav + literal('</li>')
        else:
            return make_menu_item(menu, active, is_submenu) + literal('</li>')

    navigation_html = ''
    for menu in navigation_tree:
        navigation_html += construct_menu_tree(menu, False)

    return navigation_html


def make_menu_item(menu_item, active = False, is_submenu = False):
    icon = ''
    if is_submenu:
        icon = literal('<span class="fa fa-long-arrow-right"></span>')

    link = literal('<a href="') + menu_item.get('url') + literal('">') + icon + menu_item.get('title') + literal('</a>')
    item_classes = ''

    if active:
        item_classes += 'active';
    return literal('<li class="' + item_classes + '">') + link


def get_search_tags(facets_dict, visible_fields):
    tags = {}
    for field in visible_fields:
        if facets_dict.get('fields').get(field):
            tags[field] = facets_dict.get('fields').get(field)

    return tags

def get_created_or_updated(pkg_or_res):
    newer = pkg_or_res.get('date_released', None)
    if newer is not None and 'date_updated' in pkg_or_res and pkg_or_res['date_updated'] > newer:
        return pkg_or_res['date_updated']
    elif newer is None and 'date_updated' in pkg_or_res:
        return pkg_or_res['date_updated']
    return newer

def get_cookiehub_domain_code():
    return config.get('ckanext.sixodp_ui.cookiehub_domain_code')