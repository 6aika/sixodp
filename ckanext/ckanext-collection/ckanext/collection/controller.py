import ckan.plugins as p
import ckan.lib.base as base
import ckan.lib.helpers as h
import ckan.lib.navl.dictization_functions as dict_fns
import ckan.logic as logic
import ckan.model as model
import ckan.lib.plugins
import ckan.plugins as plugins
import logging
import ckan.lib.maintain as maintain
import ckan.lib.search as search

from ckan.common import c, OrderedDict, g, request, _
from urllib import urlencode

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
lookup_group_controller = ckan.lib.plugins.lookup_group_controller

log = logging.getLogger(__name__)

class CollectionController(p.toolkit.BaseController):

    group_types = ['collection']

    def _group_form(self, group_type=None):
        return 'collection/new_collection_form.html'

    def _db_to_form_schema(self, group_type=None):
        '''This is an interface to manipulate data from the database
        into a format suitable for the form (optional)'''
        return lookup_group_plugin(group_type).db_to_form_schema()

    def _replace_group_org(self, string):
        ''' substitute organization for group if this is an org'''
        return string

    def _action(self, action_name):
        ''' select the correct group/org action '''
        return get_action(self._replace_group_org(action_name))

    def _check_access(self, action_name, *args, **kw):
        ''' select the correct group/org check_access '''
        return check_access(self._replace_group_org(action_name), *args, **kw)

    def _setup_template_variables(self, context, data_dict, group_type=None):
        return lookup_group_plugin(group_type). \
            setup_template_variables(context, data_dict)

    def _new_template(self, group_type):
        return 'collection/new.html'

    def _index_template(self, group_type):
        return 'collection/index.html'

    def _read_template(self, group_type):
        return 'collection/read.html'

    def _ensure_controller_matches_group_type(self, id):
        group = model.Group.get(id)
        if group is None:
            abort(404, _('Group not found'))
        if group.type not in self.group_types:
            abort(404, _('Incorrect group type'))
        return group.type

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


    def read(self, id, limit=20):
        group_type = self._ensure_controller_matches_group_type(
            id.split('@')[0])

        context = {'model': model, 'session': model.Session,
                   'user': c.user,
                   'schema': self._db_to_form_schema(group_type=group_type),
                   'for_view': True}
        data_dict = {'id': id, 'type': group_type}

        # unicode format (decoded from utf8)
        c.q = request.params.get('q', '')

        try:
            # Do not query for the group datasets when dictizing, as they will
            # be ignored and get requested on the controller anyway
            data_dict['include_datasets'] = False
            c.group_dict = self._action('group_show')(context, data_dict)
            c.group = context['group']
        except (NotFound, NotAuthorized):
            abort(404, _('Group not found'))

        self._read(id, limit, group_type)
        return render(self._read_template(c.group_dict['type']),
                      extra_vars={'group_type': group_type})


    def _read(self, id, limit, group_type):
        ''' This is common code used by both read and bulk_process'''
        context = {'model': model, 'session': model.Session,
                   'user': c.user,
                   'schema': self._db_to_form_schema(group_type=group_type),
                   'for_view': True, 'extras_as_string': True}

        q = c.q = request.params.get('q', '')
        # Search within group
        if c.group_dict.get('is_organization'):
            q += ' owner_org:"%s"' % c.group_dict.get('id')
        else:
            q += ' groups:"%s"' % c.group_dict.get('name')

        c.description_formatted = \
            h.render_markdown(c.group_dict.get('description'))

        context['return_query'] = True

        page = h.get_page_number(request.params)

        # most search operations should reset the page counter:
        params_nopage = [(k, v) for k, v in request.params.items()
                         if k != 'page']
        sort_by = request.params.get('sort', None)

        def search_url(params):
            controller = lookup_group_controller(group_type)
            action = 'bulk_process' if c.action == 'bulk_process' else 'read'
            url = h.url_for(controller=controller, action=action, id=id)
            params = [(k, v.encode('utf-8') if isinstance(v, basestring)
            else str(v)) for k, v in params]
            return url + u'?' + urlencode(params)

        def drill_down_url(**by):
            return h.add_url_param(alternative_url=None,
                                   controller='group', action='read',
                                   extras=dict(id=c.group_dict.get('name')),
                                   new_params=by)

        c.drill_down_url = drill_down_url

        def remove_field(key, value=None, replace=None):
            return h.remove_url_param(key, value=value, replace=replace,
                                      controller='group', action='read',
                                      extras=dict(id=c.group_dict.get('name')))

        c.remove_field = remove_field

        def pager_url(q=None, page=None):
            params = list(params_nopage)
            params.append(('page', page))
            return search_url(params)

        try:
            c.fields = []
            search_extras = {}
            for (param, value) in request.params.items():
                if param not in ['q', 'page', 'sort'] \
                        and len(value) and not param.startswith('_'):
                    if not param.startswith('ext_'):
                        c.fields.append((param, value))
                        q += ' %s: "%s"' % (param, value)
                    else:
                        search_extras[param] = value

            include_private = False
            user_member_of_orgs = [org['id'] for org
                                   in h.organizations_available('read')]

            if (c.group and c.group.id in user_member_of_orgs):
                include_private = True

            facets = OrderedDict()

            default_facet_titles = {'organization': _('Organizations'),
                                    'groups': _('Groups'),
                                    'tags': _('Tags'),
                                    'res_format': _('Formats'),
                                    'license_id': _('Licenses')}

            for facet in g.facets:
                if facet in default_facet_titles:
                    facets[facet] = default_facet_titles[facet]
                else:
                    facets[facet] = facet

            # Facet titles
            self._update_facet_titles(facets, group_type)

            if 'capacity' in facets and (group_type != 'organization' or
                                             not user_member_of_orgs):
                del facets['capacity']

            c.facet_titles = facets

            data_dict = {
                'q': q,
                'fq': '',
                'include_private': include_private,
                'facet.field': facets.keys(),
                'rows': limit,
                'sort': sort_by,
                'start': (page - 1) * limit,
                'extras': search_extras
            }

            context_ = dict((k, v) for (k, v) in context.items()
                            if k != 'schema')
            query = get_action('package_search')(context_, data_dict)

            c.page = h.Page(
                collection=query['results'],
                page=page,
                url=pager_url,
                item_count=query['count'],
                items_per_page=limit
            )

            c.group_dict['package_count'] = query['count']
            c.facets = query['facets']
            maintain.deprecate_context_item('facets',
                                            'Use `c.search_facets` instead.')

            c.search_facets = query['search_facets']
            c.search_facets_limits = {}
            for facet in c.facets.keys():
                limit = int(request.params.get('_%s_limit' % facet,
                                               g.facets_default_number))
                c.search_facets_limits[facet] = limit
            c.page.items = query['results']

            c.sort_by_selected = sort_by

        except search.SearchError, se:
            log.error('Group search error: %r', se.args)
            c.query_error = True
            c.facets = {}
            c.page = h.Page(collection=[])

        self._setup_template_variables(context, {'id': id},
                                       group_type=group_type)


    def _update_facet_titles(self, facets, group_type):
        for plugin in plugins.PluginImplementations(plugins.IFacets):
            facets = plugin.group_facets(
                facets, group_type, None)


    def new(self, data=None, errors=None, error_summary=None):
        if data and 'type' in data:
                group_type = data['type']
        else:
            group_type = 'collection'
        if data:
            data['type'] = group_type

        context = {'model': model, 'session': model.Session,
                   'user': c.user,
                   'save': 'save' in request.params,
                   'parent': request.params.get('parent', None)}
        try:
            self._check_access('group_create', context)
        except NotAuthorized:
            abort(403, _('Unauthorized to create a group'))

        if context['save'] and not data:
            return self._save_new(context, group_type)

        data = data or {}
        if not data.get('image_url', '').startswith('http'):
            data.pop('image_url', None)

        errors = errors or {}
        error_summary = error_summary or {}
        vars = {'data': data, 'errors': errors,
                'error_summary': error_summary, 'action': 'new',
                'group_type': group_type}

        self._setup_template_variables(context, data, group_type=group_type)
        c.form = render(self._group_form(group_type=group_type),
                        extra_vars=vars)
        return render(self._new_template(group_type),
                      extra_vars={'group_type': group_type})

    def _save_new(self, context, group_type=None):
        try:
            data_dict = clean_dict(dict_fns.unflatten(
                tuplize_dict(parse_params(request.params))))
            data_dict['type'] = group_type or 'group'
            context['message'] = data_dict.get('log_message', '')
            data_dict['users'] = [{'name': c.user, 'capacity': 'admin'}]
            group = self._action('group_create')(context, data_dict)

            # Redirect to the appropriate _read route for the type of group
            h.redirect_to(str('/collection/' + group['name']))
        except (NotFound, NotAuthorized), e:
            abort(404, _('Group not found'))
        except dict_fns.DataError:
            abort(400, _(u'Integrity Error'))
        except ValidationError, e:
            errors = e.error_dict
            error_summary = e.error_summary
            return self.new(data_dict, errors, error_summary)


    def dataset_collection_list(self, id):
        '''
        Display a list of collections a dataset is associated with, with an
        option to add to collection from a list.
        '''
        context = {'model': model, 'session': model.Session,
                   'user': c.user, 'for_view': True,
                   'auth_user_obj': c.userobj, 'use_cache': False}
        data_dict = {'id': id}
        try:
            c.pkg_dict = get_action('package_show')(context, data_dict)
            dataset_type = c.pkg_dict['type'] or 'dataset'
        except (NotFound, NotAuthorized):
            abort(404, _('Dataset not found'))

        if request.method == 'POST':
            # Adding package to collection
            new_collection = request.POST.get('group_added')
            log.info(new_collection)
            if new_collection:
                data_dict = {"id": new_collection,
                             "object": id,
                             "object_type": 'package',
                             "capacity": 'public'}
                try:
                    get_action('member_create')(context, data_dict)
                except NotFound:
                    abort(404, _('Collection not found'))

            removed_group = None
            for param in request.POST:
                if param.startswith('group_remove'):
                    removed_group = param.split('.')[-1]
                    break
            if removed_group:
                data_dict = {"id": removed_group,
                             "object": id,
                             "object_type": 'package'}

                try:
                    get_action('member_delete')(context, data_dict)
                except NotFound:
                    abort(404, _('Collection not found'))
            h.redirect_to(controller='ckanext.collection.controller:CollectionController', action='dataset_collection_list', id=id)

        context['is_member'] = True
        users_groups = get_action('group_list_authz')(context, data_dict)

        pkg_group_ids = set(group['id'] for group
                            in c.pkg_dict.get('groups', []))
        user_group_ids = set(group['id'] for group
                             in users_groups)

        c.collection_dropdown = [[group['id'], group['display_name']]
                            for group in users_groups if
                            group['id'] not in pkg_group_ids and group['type'] == 'collection']


        for group in c.pkg_dict.get('groups', []):
            group['user_member'] = (group['id'] in user_group_ids)

        return render('package/collection_list.html',
                      {'dataset_type': dataset_type})