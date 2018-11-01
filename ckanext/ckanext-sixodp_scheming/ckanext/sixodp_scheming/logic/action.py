import sys
import imp

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