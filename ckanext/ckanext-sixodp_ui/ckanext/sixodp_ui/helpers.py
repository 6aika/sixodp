import logging
import httplib
from pylons import config
import json
import ckan.lib.i18n as i18n

log = logging.getLogger(__name__)

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
