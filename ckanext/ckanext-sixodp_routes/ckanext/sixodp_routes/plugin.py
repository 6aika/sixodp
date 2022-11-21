import ckan
from ckan.common import c, _, request, g
import ckan.model as model
import ckan.lib.navl.dictization_functions as dictization_functions
import ckan.logic as logic
import ckan.lib.base as base
from ckan.plugins import toolkit
from .views import sixodp

abort = base.abort
render = base.render
check_access = ckan.logic.check_access
NotAuthorized = ckan.logic.NotAuthorized
NotFound = ckan.logic.NotFound
get_action = ckan.logic.get_action
config = toolkit.config

unflatten = dictization_functions.unflatten
DataError = dictization_functions.DataError

UsernamePasswordError = logic.UsernamePasswordError
ValidationError = logic.ValidationError

lookup_group_controller = ckan.lib.plugins.lookup_group_controller

import logging

log = logging.getLogger(__name__)


def admin_only(context, data_dict=None):
    return {'success': False, 'msg': 'Access restricted to system administrators'}


class Sixodp_RoutesPlugin(ckan.plugins.SingletonPlugin):
    ckan.plugins.implements(ckan.plugins.IAuthFunctions)
    ckan.plugins.implements(ckan.plugins.IBlueprint)


    # IAuthFunctions

    def get_auth_functions(self):
        return {'user_list': admin_only,
                'revision_list': admin_only,
                'revision_diff': admin_only,
                'package_revision_list': admin_only
                }

    # IBlueprint
    def get_blueprints(self):
        return [sixodp]


def auth_context():
    return {'model': ckan.model,
            'user': c.user or c.author,
            'auth_user_obj': c.userobj}


#TODO: add sorting
# if q and not sort_by:
#                 sort_by = 'score desc, metadata_modified desc'
#             elif not sort_by:
#                 sort_by = 'metadata_created desc'
@toolkit.chained_action
def package_search(original_action, context, data_dict):
    return original_action(context, data_dict)