import logging
import httplib

log = logging.getLogger(__name__)

def get_main_navigation_items():
    connection = httplib.HTTPConnection("dataportaali.demo.site")
    connection.request("GET", "/wp-json/wp-api-menus/v2/menus/2")
    response_data_dict = eval(connection.getresponse().read())
    connection.close()

    navigation_items = []
    if(response_data_dict.get('items')):
        for item in response_data_dict['items']:
            navigation_items.append({
                'title': item.get('title'),
                'url': item.get('url')
            })
    return navigation_items
