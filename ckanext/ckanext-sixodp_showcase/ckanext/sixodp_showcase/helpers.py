import ckan.model as model
from ckan.plugins import toolkit as tk

c = tk.c
get_action = tk.get_action

def get_featured_showcases():
    context = {'model': model, 'user': c.user, 'auth_user_obj': c.userobj}
    limit = 4

    data_dict = {
        'fq': 'featured:true +dataset_type:showcase',
        'rows': limit,
        'start': 0,
        'sort': 'metadata_created desc',
        'include_private': False
    }

    query = get_action('package_search')(context, data_dict)

    return query['results']