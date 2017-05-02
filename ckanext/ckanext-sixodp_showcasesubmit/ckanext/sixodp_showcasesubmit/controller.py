import logging
import ckan.plugins as p
import ckan.logic as logic
import ckan.lib.base as base
import ckan.lib.helpers as h
import ckan.model as model
import ckan.lib.navl.dictization_functions as dict_fns
from ckan.common import _, request, c, response
from ckan.common import config

log = logging.getLogger(__name__)

check_access = logic.check_access
get_action = logic.get_action
render = base.render
abort = base.abort
flatten_to_string_key = logic.flatten_to_string_key
NotFound = logic.NotFound
NotAuthorized = logic.NotAuthorized
ValidationError = logic.ValidationError
clean_dict = logic.clean_dict
tuplize_dict = logic.tuplize_dict
parse_params = logic.parse_params

def index_template():
    return 'sixodp_showcasesubmit/base_form_page.html'

class Sixodp_ShowcasesubmitController(p.toolkit.BaseController):

    def index(self):
        return render(index_template())

    @staticmethod
    def _submit():
        try:
            username = config.get('ckanext.sixodp_showcasesubmit.creating_user_username')
            user = model.User.get(username)

            context = {'model': model, 'session': model.Session,
                       'user': user.id, 'auth_user_obj': user.id,
                       'save': 'save' in request.params}

            parsedParams = dict_fns.unflatten(tuplize_dict(parse_params(
                request.params)))

            name = parsedParams.get('title').replace(" ", "-").lower()

            data_dict = {
                'type': 'showcase',
                'title': parsedParams.get('title'),
                'name': name,
                'platform': parsedParams.get('platform'),
                'author': parsedParams.get('author'),
                'application_website': parsedParams.get('application_website'),
                'store_urls': parsedParams.get('store_urls'),
                'notes_translated': {
                    'fi': parsedParams.get('notes_translated-fi'),
                    'en': '',
                    'sv': ''
                },
                'featured': False,
                'archived': False,
                'private': True
            }

            get_action('package_create')(context, data_dict)
        except NotAuthorized:
            abort(403, _('Unauthorized to create a package'))
        except ValidationError, e:
            errors = e.error_dict
            error_summary = e.error_summary
            data_dict['state'] = 'none'
            return data_dict, errors, error_summary

        return data_dict, [], {}

    def ajax_submit(self):
        data, errors, error_summary = self._submit()
        data = flatten_to_string_key({ 'data': data, 'errors': errors, 'error_summary': error_summary })
        response.headers['Content-Type'] = 'application/json;charset=utf-8'
        return h.json.dumps(data)

    def submit(self):
        data, errors, error_summary = self._submit()
        vars = {'data': data, 'errors': errors,
                'error_summary': error_summary}
        return render(index_template(), extra_vars=vars)

