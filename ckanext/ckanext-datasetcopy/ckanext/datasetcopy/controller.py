import logging

import ckan.logic as logic
import ckan.lib.base as base
import ckan.lib.navl.dictization_functions as dict_fns
import ckan.lib.helpers as h
import ckan.model as model
import ckan.lib.plugins
import ckan.plugins as p

from ckan.common import OrderedDict, _, json, request, c, g, response

log = logging.getLogger(__name__)

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

lookup_package_plugin = ckan.lib.plugins.lookup_package_plugin

class DatasetcopyController(p.toolkit.BaseController):

    def _package_form(self, package_type=None):
        return lookup_package_plugin(package_type).package_form()

    def _setup_template_variables(self, context, data_dict, package_type=None):
        return lookup_package_plugin(package_type). \
            setup_template_variables(context, data_dict)

    def _copy_template(self, package_type):
        return 'datasetcopy/copy.html'

    def _get_package_type(self, id):
        """
        Given the id of a package this method will return the type of the
        package, or 'dataset' if no type is currently set
        """
        pkg = model.Package.get(id)
        if pkg:
            return pkg.type or 'dataset'
        return None

    # def copy_package(self, id, data=None, errors=None, error_summary=None):
    #
    #     context = {'model': model, 'session': model.Session,
    #                'user': c.user or c.author, 'extras_as_string': True,
    #                'save': 'save' in request.params,}
    #
    #     if context['save'] and not data:
    #         return self._save_copy(id, context)
    #     try:
    #         check_access('package_create',context)
    #         c.pkg_dict = get_action('package_show')(context, {'id':id})
    #         old_data = get_action('package_show')(context, {'id':id})
    #         # old data is from the database and data is passed from the
    #         # user if there is a validation error. Use users data if there.
    #
    #         data = data or old_data
    #         if 'resources' in data.keys():
    #             del data['resources']
    #
    #         # Unwrap notes from a dictionary list format.
    #         tags = []
    #         for tag_dict in data['tags']:
    #             tags.append(tag_dict['name'])
    #
    #         data['tag_string'] = c.pkg_dict['tag_string'] = ','.join(tags)
    #
    #     except NotAuthorized:
    #         abort(401, _('Unauthorized to read or create package %s') % '')
    #     except NotFound:
    #         abort(404, _('Dataset not found'))
    #
    #     c.pkg = context.get("package")
    #
    #     errors = errors or {}
    #     error_summary = error_summary or {}
    #     vars = {'data': data, 'errors': errors, 'error_summary': error_summary, 'stage': ['active'], 'action': 'edit'}
    #     c.errors_json = json.dumps(errors)
    #
    #     # self._setup_template_variables(context, {})
    #
    #     c.form = render('datasetcopy/snippets/package_copy_form.html', extra_vars=vars)
    #
    #     return render('datasetcopy/copy.html')


    def copy_package(self, id, data=None, errors=None, error_summary=None):
        package_type = self._get_package_type(id)
        context = {'model': model, 'session': model.Session,
                   'user': c.user, 'auth_user_obj': c.userobj,
                   'save': 'save' in request.params}

        if context['save'] and not data:
            return self._save_edit(id, context, package_type=package_type)
        try:
            c.pkg_dict = get_action('package_show')(dict(context,
                                                         for_view=True),
                                                    {'id': id})
            context['for_edit'] = True
            old_data = get_action('package_show')(context, {'id': id})
            # old data is from the database and data is passed from the
            # user if there is a validation error. Use users data if there.
            if data:
                old_data.update(data)
            data = old_data
        except (NotFound, NotAuthorized):
            abort(404, _('Dataset not found'))
        # are we doing a multiphase add?
        if data.get('state', '').startswith('draft'):
            c.form_action = h.url_for(controller='package', action='new')
            c.form_style = 'new'
            return self.new(data=data, errors=errors,
                            error_summary=error_summary)

        c.pkg = context.get("package")
        c.resources_json = h.json.dumps(data.get('resources', []))

        try:
            check_access('package_update', context)
        except NotAuthorized:
            abort(403, _('User %r not authorized to edit %s') % (c.user, id))
        # convert tags if not supplied in data
        if data and not data.get('tag_string'):
            data['tag_string'] = ', '.join(h.dict_list_reduce(
                c.pkg_dict.get('tags', {}), 'name'))
        errors = errors or {}
        form_snippet = self._package_form(package_type=package_type)
        form_vars = {'data': data, 'errors': errors,
                     'error_summary': error_summary, 'action': 'edit',
                     'dataset_type': package_type,
                     }
        c.errors_json = h.json.dumps(errors)

        self._setup_template_variables(context, {'id': id},
                                       package_type=package_type)

        # we have already completed stage 1
        form_vars['stage'] = ['active']
        if data.get('state', '').startswith('draft'):
            form_vars['stage'] = ['active', 'complete']

        copy_template = self._copy_template(package_type)
        return render(copy_template,
                      extra_vars={'form_vars': form_vars,
                                  'form_snippet': form_snippet,
                                  'dataset_type': package_type})



    def copy_package_save(self, name_or_id, context, package_type=None):
        from ckan.lib.search import SearchIndexError
        log.debug('Package save request name: %s POST: %r',
                  name_or_id, request.POST)
        try:
            data_dict = clean_dict(dict_fns.unflatten(
                tuplize_dict(parse_params(request.POST))))
            if '_ckan_phase' in data_dict:
                # we allow partial updates to not destroy existing resources
                context['allow_partial_update'] = True
                if 'tag_string' in data_dict:
                    data_dict['tags'] = self._tag_string_to_list(
                        data_dict['tag_string'])
                del data_dict['_ckan_phase']
                del data_dict['save']
            context['message'] = data_dict.get('log_message', '')
            data_dict['id'] = name_or_id
            pkg = get_action('package_update')(context, data_dict)
            c.pkg = context['package']
            c.pkg_dict = pkg

            self._form_save_redirect(pkg['name'], 'edit',
                                     package_type=package_type)
        except NotAuthorized:
            abort(403, _('Unauthorized to read package %s') % id)
        except NotFound, e:
            abort(404, _('Dataset not found'))
        except dict_fns.DataError:
            abort(400, _(u'Integrity Error'))
        except SearchIndexError, e:
            try:
                exc_str = unicode(repr(e.args))
            except Exception:  # We don't like bare excepts
                exc_str = unicode(str(e))
            abort(500, _(u'Unable to update search index.') + exc_str)
        except ValidationError, e:
            errors = e.error_dict
            error_summary = e.error_summary
            return self.edit(name_or_id, data_dict, errors, error_summary)
