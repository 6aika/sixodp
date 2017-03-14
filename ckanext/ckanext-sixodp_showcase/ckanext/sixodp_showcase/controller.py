from ckanext.showcase.controller import ShowcaseController
from ckan import model
from ckan.plugins import toolkit as tk
from ckan import logic

import ckan.lib.base as base

_ = tk._
c = tk.c
request = tk.request
render = tk.render
abort = tk.abort
redirect = base.redirect
NotFound = tk.ObjectNotFound
ValidationError = tk.ValidationError
check_access = tk.check_access
get_action = tk.get_action
tuplize_dict = logic.tuplize_dict
clean_dict = logic.clean_dict
parse_params = logic.parse_params
NotAuthorized = tk.NotAuthorized

import logging

log = logging.getLogger(__name__)

class Sixodp_ShowcaseController(ShowcaseController):

    def new(self, data=None, errors=None, error_summary=None):
        log.info("In sixodp showcase controller new")
        return super(Sixodp_ShowcaseController, self).new(data=data, errors=errors,
                                                          error_summary=error_summary)
    def read(self, id, format='html'):
        '''
        Detail view for a single showcase, listing its associated datasets.
        '''

        context = {'model': model, 'session': model.Session,
                   'user': c.user or c.author, 'for_view': True,
                   'auth_user_obj': c.userobj}
        data_dict = {'id': id}

        # check if showcase exists
        try:
            c.pkg_dict = get_action('package_show')(context, data_dict)
        except NotFound:
            abort(404, _('Showcase not found'))
        except NotAuthorized:
            abort(401, _('Unauthorized to read showcase'))

        # get showcase packages
        c.showcase_pkgs = get_action('ckanext_showcase_package_list')(
            context, {'showcase_id': c.pkg_dict['id']})

        return render("sixodp_showcase/read.html",
                      extra_vars={'dataset_type': 'showcase'})

    def edit(self, id, data=None, errors=None, error_summary=None):
        log.info("In sixodp showcase controller edit")
        return super(Sixodp_ShowcaseController, self).edit(id, data=data, errors=errors, error_summary=error_summary)

    def _guess_package_type(self, expecting_name=False):
        """Showcase packages are always DATASET_TYPE_NAME."""
        log.info("Guessing")
        return 'showcase'

    def _search_template(self, package_type):
        return "sixodp_showcase/search.html"