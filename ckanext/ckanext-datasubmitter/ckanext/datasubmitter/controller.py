import logging
import ckan.plugins as p
import ckan.logic as logic
import ckan.lib.base as base
import ckan.lib.helpers as h
import ckan.model as model
import datetime
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
    return 'datasubmitter/base_form_page.html'

class DatasubmitterController(p.toolkit.BaseController):

    def index(self):
        vars = {'data': {}, 'errors': [],
                'error_summary': {}, 'message': None}
        return render(index_template(), extra_vars=vars)

    @staticmethod
    def _submit():
        try:
            username = config.get('ckanext.datasubmitter.creating_user_username')
            user = model.User.get(username)

            organization_reference = config.get('ckanext.datasubmitter.organization_name_or_id')
            organization = model.Group.get(organization_reference)

            context = {'model': model, 'session': model.Session,
                       'user': user.id, 'auth_user_obj': user.id,
                       'save': 'save' in request.params}

            parsedParams = dict_fns.unflatten(tuplize_dict(parse_params(
                request.params)))

            name = parsedParams.get('title_translated-fi').replace(" ", "-").lower()

            data_dict = {
                'type': 'dataset',
                'name': name,
                'title_translated': {
                    'fi': parsedParams.get('title_translated-fi'),
                    'en': 'UPDATE THIS BEFORE PUBLISHING',
                    'sv': 'UPDATE THIS BEFORE PUBLISHING'
                },
                'notes_translated': {
                    'fi': parsedParams.get('notes_translated-fi'),
                    'en': '',
                    'sv': ''
                },
                'owner_org': organization.id,
                'geographical_coverage': ["update this before publishing"],
                'date_released': datetime.date.today().strftime("%Y-%m-%d"),
                'date_updated': datetime.date.today().strftime("%Y-%m-%d"),
                'maintainer': parsedParams.get('maintainer'),
                'maintainer_email': parsedParams.get('maintainer_email'),
                'license_id': 'other-open',
                'private': True
            }

            get_action('package_create')(context, data_dict)
        except NotAuthorized:
            abort(403, _('Unauthorized to create a package'))
        except ValidationError, e:
            errors = e.error_dict
            error_summary = e.error_summary
            data_dict['state'] = 'none'
            return data_dict, errors, error_summary, None

        return {}, [], {}, {'class': 'success', 'text': _('Dataset submitted successfully')}

    def ajax_submit(self):
        data, errors, error_summary, message = self._submit()
        data = flatten_to_string_key({ 'data': data, 'errors': errors, 'error_summary': error_summary, 'message': message })
        response.headers['Content-Type'] = 'application/json;charset=utf-8'
        return h.json.dumps(data)

    def submit(self):
        data, errors, error_summary, message = self._submit()
        vars = {'data': data, 'errors': errors,
                'error_summary': error_summary, 'message': message}
        return render(index_template(), extra_vars=vars)

