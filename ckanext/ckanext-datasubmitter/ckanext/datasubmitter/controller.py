import logging
import ckan.plugins as p
import ckan.logic as logic
import ckan.lib.base as base
import ckan.lib.helpers as h
import ckan.model as model
import datetime
import ckan.lib.navl.dictization_functions as dict_fns
import httplib
import json
import urllib
import re
from ckan.common import _, request, c, response
from ckan.common import config
from ckan.lib.mailer import mail_recipient
from ckanext.sixodp_ui.helpers import get_current_lang

log = logging.getLogger(__name__)

check_access = logic.check_access
get_action = logic.get_action
render = base.render
abort = base.abort
redirect_to = p.toolkit.redirect_to
flatten_to_string_key = logic.flatten_to_string_key
NotFound = logic.NotFound
NotAuthorized = logic.NotAuthorized
ValidationError = logic.ValidationError
clean_dict = logic.clean_dict
tuplize_dict = logic.tuplize_dict
parse_params = logic.parse_params

def index_template():
    return 'datasubmitter/base_form_page.html'

def validateReCaptcha(recaptcha_response):
    response_data_dict = {}
    try:
        connection = httplib.HTTPSConnection('google.com')
        params = urllib.urlencode({
            'secret': config.get('ckanext.datasubmitter.recaptcha_secret'),
            'response': recaptcha_response,
            'remoteip': p.toolkit.request.environ.get('REMOTE_ADDR')
        })
        headers = {'Content-type': 'application/x-www-form-urlencoded', 'Accept': 'text/plain'}
        connection.request('POST', '/recaptcha/api/siteverify', params, headers)
        response_data_dict = json.loads(connection.getresponse().read())
        connection.close()

        if(response_data_dict.get('success') != True):
            raise ValidationError('Google reCaptcha validation failed')
    except Exception, e:
        log.error('Connection to Google reCaptcha API failed')
        raise ValidationError('Connection to Google reCaptcha API failed, unable to validate captcha')


def sendNewDatasetNotifications(package_name):
    recipient_emails = config.get('ckanext.datasubmitter.recipient_emails').split(' ')
    dataset_url = config.get('ckan.site_url') + h.url_for(
        controller='package',
        action='read', id=package_name)

    message_body = _('A user has submitted a new dataset') + ': ' + dataset_url

    for email in recipient_emails:
        mail_recipient("", email, _('New dataset notification'), message_body)


class DatasubmitterController(p.toolkit.BaseController):

    def index(self):
        vars = {'data': {}, 'errors': [],
                'error_summary': {}, 'message': None}
        return render(index_template(), extra_vars=vars)

    @staticmethod
    def _submit():
        user_entered_notes = ''

        try:
            username = config.get('ckanext.datasubmitter.creating_user_username')
            user = model.User.get(username)

            organization_reference = config.get('ckanext.datasubmitter.organization_name_or_id')
            organization = model.Group.get(organization_reference)
            if user is None or organization is None:
                abort(403,_('Dataset submit user or organization not set.'))

            context = {'model': model, 'session': model.Session,
                       'user': user.id, 'auth_user_obj': user.id,
                       'save': 'save' in request.params}

            data_dict = clean_dict(dict_fns.unflatten(
                tuplize_dict(parse_params(request.POST))))

            user_entered_notes = data_dict.get('notes_translated-fi')

            data_dict['type'] = 'dataset'
            data_dict['name'] = re.sub('[^a-z0-9]+', '', data_dict.get('title_translated-fi')) \
                if data_dict.get('title_translated-fi') else None
            data_dict['keywords'] = {
                'fi': ['UPDATE THIS BEFORE PUBLISHING'],
                'en': [],
                'sv': []
            }
            data_dict['geographical_coverage'] = ['UPDATE THIS BEFORE PUBLISHING']
            data_dict['owner_org'] = organization.id
            data_dict['date_released'] = datetime.date.today().strftime('%Y-%m-%d')
            data_dict['license_id'] = 'other-open'
            data_dict['private'] = True

            if data_dict.get('organization'):
                data_dict['notes_translated-fi'] += '\n\n' + _('Organization') + ': ' + data_dict.get('organization')

            if data_dict.get('url'):
                data_dict['notes_translated-fi'] += '\n\n' + _('Dataset url') + ': ' + data_dict.get('url')

            validateReCaptcha(data_dict.get('g-recaptcha-response'))

            get_action('package_create')(context, data_dict)
        except NotAuthorized:
            abort(403, _('Unauthorized to create a package'))
        except ValidationError, e:
            # Restore original user entered notes to prevent user from seeing the appended info
            data_dict['notes_translated-fi'] = user_entered_notes

            errors = e.error_dict
            error_summary = e.error_summary
            data_dict['state'] = 'none'
            return data_dict, errors, error_summary, None

        sendNewDatasetNotifications(data_dict['name'])

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

        if errors:
            return render(index_template(), extra_vars=vars)

        lang = get_current_lang()
        if lang == 'sv':
            redirect_to('/sv/tack')
        elif lang == 'en_GB':
            redirect_to('/en_gb/thank-you')
        else:
            redirect_to('/kiitos')

