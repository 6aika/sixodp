import sys

import ckan.logic as logic
from ckan import model
from ckan.plugins import toolkit

_check_access = logic.check_access
get_action = logic.get_action
chained_action = logic.chained_action

import logging
log = logging.getLogger(__name__)

@logic.validate(logic.schema.default_autocomplete_schema)
def package_autocomplete(context, data_dict):
    '''Return a list of datasets (packages) that match a string.
    Datasets with names or titles that contain the query string will be
    returned.
    :param q: the string to search for
    :type q: string
    :param limit: the maximum number of resource formats to return (optional,
        default: 10)
    :type limit: int
    :rtype: list of dictionaries
    '''
    _check_access('package_autocomplete', context, data_dict)

    limit = data_dict.get('limit', 10)
    q = data_dict['q']
    q_lower = q.lower()

    search_dict = {
        'q': q,
        'include_private': False,
        'rows': limit,
        'sort': 'name asc'
    }

    packages = get_action('package_search')(context, search_dict)
    pkg_list = []
    for package in packages.get('results'):

        if package.get('name').startswith(q_lower):
            match_field = 'name'
            match_displayed = package.get('name')
        else:
            match_field = 'title'
            match_displayed = '%s (%s)' % (package.get('title'), package.get('name'))
        result_dict = {
            'name': package.get('name'),
            'title': package.get('title'),
            'match_field': match_field,
            'match_displayed': match_displayed}
        pkg_list.append(result_dict)

    return pkg_list


@chained_action
def resource_create(next_func, context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return next_func(context, datadict)


@chained_action
def resource_update(next_func, context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return next_func(context, datadict)


@chained_action
def resource_delete(next_func, context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return next_func(context, datadict)


@chained_action
def package_resource_reorder(next_func, context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return next_func(context, datadict)


@chained_action
def package_patch(next_func, context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return next_func(context, datadict)


# Adds new users to every group and collection
@chained_action
def user_create(original_action, context, data_dict):
    result = original_action(context, data_dict)


    if result:
        context = {'model': model, 'session': model.Session, 'ignore_auth': True}
        admin_user = toolkit.get_action('get_site_user')(context, None)
        context['user'] = admin_user['name']

        groups = toolkit.get_action('group_list')(context, {})
        collections = toolkit.get_action('group_list')(context,  {'type': "collection"})

        for group in groups + collections:
            member_data = {'id': group, 'username': result['name'], 'role': 'member'}
            toolkit.get_action('group_member_create')(context, member_data)

    return result

@chained_action
def group_create(original_action, context, data_dict):
    result = original_action(context, data_dict)

    current_user = context.get('user')
    if result:
        context = {'model': model, 'session': model.Session, 'ignore_auth': True}
        admin_user = toolkit.get_action('get_site_user')(context, None)
        context['user'] = admin_user['name']

        users = toolkit.get_action('user_list')(context, {'all_fields': False})
        for user in users:

            # do not modify the permissions of user creating the group
            if current_user != user:
                member_data = {'id': result['id'], 'username': user, 'role': 'member'}
                toolkit.get_action('group_member_create')(context, member_data)

    return result