import datetime
import re

import requests
from flask import Blueprint
from flask.views import MethodView

from ckan import model, logic
import ckan.lib.navl.dictization_functions as dict_fns
from ckan.lib.mailer import mail_recipient
import ckan.lib.helpers as h

from ckan.plugins.toolkit import render, redirect_to, config, abort, request, _, ValidationError, get_action, NotAuthorized
from ckanext.sixodp.helpers import get_current_lang

import logging
log = logging.getLogger(__name__)

clean_dict = logic.clean_dict
tuplize_dict = logic.tuplize_dict
parse_params = logic.parse_params

datasubmitter = Blueprint('datasubmitter', __name__)


def validateReCaptcha(recaptcha_response):
    try:
        recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'
        payload = {
            'secret': config.get('ckanext.datasubmitter.recaptcha_secret'),
            'response': recaptcha_response,
            'remoteip': request.remote_addr
        }


        response = requests.post(recaptcha_url, data = payload)
        result = response.json()

        if not result.get('success'):
            raise ValidationError('Google reCaptcha validation failed')
    except Exception as e:
        log.error('Connection to Google reCaptcha API failed')
        raise ValidationError('Connection to Google reCaptcha API failed, unable to validate captcha')

def sendNewDatasetNotifications(package_name):
    recipient_emails = config.get('ckanext.datasubmitter.recipient_emails').split(' ')
    dataset_url = config.get('ckan.site_url') + h.url_for('dataset.read', id=package_name)

    message_body = _('A user has submitted a new dataset') + ': ' + dataset_url

    for email in recipient_emails:
        mail_recipient("", email, _('New dataset notification'), message_body)


class DatasubmitterView(MethodView):
    def get(self):
        extra_vars = {'data': {}, 'errors': [],
                'error_summary': {}, 'message': None}
        return render('datasubmitter/base_form_page.html', extra_vars=extra_vars)

    def post(self):
        data, errors, error_summary, message = self._submit()
        extra_vars = {'data': data, 'errors': errors,
                'error_summary': error_summary, 'message': message}


        if errors:
            return render('datasubmitter/base_form_page.html', extra_vars=extra_vars)

        lang = get_current_lang()
        if lang == 'sv':
            return redirect_to('/sv/tack')
        elif lang == 'en_GB':
            return redirect_to('/en_gb/thank-you')
        else:
            return redirect_to('/kiitos')

    def _submit(self):
        user_entered_notes = ''

        try:
            username = config.get('ckanext.datasubmitter.creating_user_username')
            user = model.User.get(username)

            organization_reference = config.get('ckanext.datasubmitter.organization_name_or_id')
            organization = model.Group.get(organization_reference)
            if user is None or organization is None:
                log.info('Dataset submit user or organization not set.')
                return {}, [], {}, None

            context = {'model': model, 'session': model.Session,
                       'user': user.id, 'auth_user_obj': user.id,
                       'save': 'save' in request.args}

            data_dict = clean_dict(dict_fns.unflatten(
                tuplize_dict(parse_params(request.form))))

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
            log.info('Unauthorized to create a package')
            return {}, [], {}, None
        except ValidationError as e:
            # Restore original user entered notes to prevent user from seeing the appended info
            data_dict['notes_translated-fi'] = user_entered_notes

            errors = e.error_dict
            error_summary = e.error_summary
            data_dict['state'] = 'none'
            return data_dict, errors, error_summary, None

        sendNewDatasetNotifications(data_dict['name'])

        return {}, [], {}, {'class': 'success', 'text': _('Dataset submitted successfully')}


datasubmitter.add_url_rule('/submit-data', view_func=DatasubmitterView.as_view('index'), strict_slashes=False)



