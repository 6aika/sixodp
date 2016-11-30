import ckan.plugins as p
import ckan.lib.base as base
import ckan.lib.helpers as h
import ckan.logic as logic
import ckan.model as model
import ckan.lib.plugins
from ckan.common import c, request, _

h = base.h
c = p.toolkit.c
flatten_to_string_key = logic.flatten_to_string_key

render = base.render
abort = base.abort

NotFound = logic.NotFound
NotAuthorized = logic.NotAuthorized
ValidationError = logic.ValidationError
check_access = logic.check_access
get_action = logic.get_action
tuplize_dict = logic.tuplize_dict
clean_dict = logic.clean_dict
parse_params = logic.parse_params

lookup_group_plugin = ckan.lib.plugins.lookup_group_plugin

class CollectionController(p.toolkit.BaseController):

    def _replace_group_org(self, string):
        ''' substitute organization for group if this is an org'''
        return string

    def _action(self, action_name):
        ''' select the correct group/org action '''
        return get_action(self._replace_group_org(action_name))

    def _check_access(self, action_name, *args, **kw):
        ''' select the correct group/org check_access '''
        return check_access(self._replace_group_org(action_name), *args, **kw)

    def _index_template(self, group_type):
        return 'collection/index.html'

    def search_collection(self):

        group_type = 'collection'

        page = h.get_page_number(request.params) or 1
        items_per_page = 21

        context = {'model': model, 'session': model.Session,
                   'user': c.user, 'for_view': True,
                   'with_private': False}

        q = c.q = request.params.get('q', '')
        sort_by = c.sort_by_selected = request.params.get('sort')
        try:
            self._check_access('site_read', context)
            self._check_access('group_list', context)
        except NotAuthorized:
            abort(403, _('Not authorized to see this page'))

        # pass user info to context as needed to view private datasets of
        # orgs correctly
        if c.userobj:
            context['user_id'] = c.userobj.id
            context['user_is_admin'] = c.userobj.sysadmin

        data_dict_global_results = {
            'all_fields': False,
            'q': q,
            'sort': sort_by,
            'type': group_type or 'group',
        }
        global_results = self._action('group_list')(context,
                                                    data_dict_global_results)

        data_dict_page_results = {
            'all_fields': True,
            'q': q,
            'sort': sort_by,
            'type': group_type or 'group',
            'limit': items_per_page,
            'offset': items_per_page * (page - 1),
        }
        page_results = self._action('group_list')(context,
                                                  data_dict_page_results)

        c.page = h.Page(
            collection=global_results,
            page=page,
            url=h.pager_url,
            items_per_page=items_per_page,
        )

        c.page.items = page_results
        return render(self._index_template(group_type),
                      extra_vars={'group_type': group_type})
