import sys

import ckan.logic as logic

_check_access = logic.check_access
get_action = logic.get_action

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


# Can be replaced with @chained_action once ckan supports chaining core actions https://github.com/ckan/ckan/pull/4509
def resource_create(context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return get_original_method('ckan.logic.action.create', 'resource_create')(context, datadict)

def resource_update(context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return get_original_method('ckan.logic.action.update', 'resource_update')(context, datadict)

def resource_delete(context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return get_original_method('ckan.logic.action.delete', 'resource_delete')(context, datadict)


def package_resource_reorder(context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return get_original_method('ckan.logic.action.update', 'package_resource_reorder')(context, datadict)

def package_patch(context, datadict):
    context['keep_deletable_attributes_in_api'] = True

    return get_original_method('ckan.logic.action.patch', 'package_patch')(context, datadict)

def get_original_method(module_name, method_name):
    """ In CKAN you cannot call original action when you override it.
        This method fixes the problem.
        Example get_original_method('ckan.logic.action.create', 'user_create')
    """
    __import__(module_name)
    imported_module = sys.modules[module_name]
    reimport_module = imp.load_compiled('%s.reimport' % module_name, imported_module.__file__)

    return getattr(reimport_module, method_name)