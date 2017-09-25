import logging
import ckan.plugins as p
import ckan.logic as logic
import ckan.lib.base as base
import ckan.lib.helpers as h
import ckan.model as model
import httplib
import json
import urllib
import re
import ckan.lib.navl.dictization_functions as dict_fns
from ckan.common import _, c, request, response
from ckan.common import config
from ckan.lib.mailer import mail_recipient

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

def validateReCaptcha(recaptcha_response):
    response_data_dict = {}
    try:
        connection = httplib.HTTPSConnection('google.com')
        params = urllib.urlencode({
            'secret': config.get('ckanext.sixodp_showcasesubmit.recaptcha_secret'),
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


def sendNewShowcaseNotifications(showcase_name):
    recipient_emails = config.get('ckanext.sixodp_showcasesubmit.recipient_emails').split(' ')
    showcase_url = config.get('ckan.site_url') + h.url_for(
        controller='ckanext.showcase.controller:ShowcaseController',
        action='read', id=showcase_name)

    message_body = _('A user has submitted a new showcase') + ': ' + showcase_url

    for email in recipient_emails:
        mail_recipient(email, email, _('New showcase notification'), message_body)


class Sixodp_ShowcasesubmitController(p.toolkit.BaseController):

    def index(self):
        vars = {'data': {}, 'errors': {},
                'error_summary': {}, 'message': None}
        return render(index_template(), extra_vars=vars)


    @staticmethod
    def _submit():
        try:
            username = config.get('ckanext.sixodp_showcasesubmit.creating_user_username')
            user = model.User.get(username)

            context = {'model': model, 'session': model.Session,
                       'user': user.id, 'auth_user_obj': user.id,
                       'save': 'save' in request.params}

            data_dict = clean_dict(dict_fns.unflatten(
                tuplize_dict(parse_params(request.POST))))

            data_dict['type'] = 'showcase'
            data_dict['name'] = re.sub('[^a-z0-9]+', '', data_dict.get('title'))
            data_dict['featured'] = False
            data_dict['archived'] = False
            data_dict['private'] = True
            data_dict['category'] = {
                'fi': ['Ilmoitetut'],
                'en': [],
                'sv': []
            }

            validateReCaptcha(data_dict.get('g-recaptcha-response'))

            new_showcase = get_action('ckanext_showcase_create')(context, data_dict)

            if data_dict.get('datasets'):
                datasets_to_link = data_dict.get('datasets').split(',')

                for package_name in datasets_to_link:
                    association_dict = {"showcase_id": new_showcase.get('id'),
                                 "package_id": package_name}
                    try:
                        get_action('ckanext_showcase_package_association_create')(
                            context, association_dict)
                    except:
                        new_showcase['notes_translated-fi'] += '\n\n' + _('N.B. The following dataset could not be automatically linked') + ': ' + package_name
                        get_action('ckanext_showcase_update')(context, new_showcase)

        except NotAuthorized:
            abort(403, _('Unauthorized to create a package'))
        except ValidationError, e:
            errors = e.error_dict
            error_summary = e.error_summary
            data_dict['state'] = 'none'
            return data_dict, errors, error_summary, None

        sendNewShowcaseNotifications(data_dict.get('name'))

        return {}, {}, {}, { 'class': 'success', 'text':  _('Showcase submitted successfully')}

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

