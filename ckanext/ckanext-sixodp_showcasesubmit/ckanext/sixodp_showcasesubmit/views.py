import re

import requests
from flask import Blueprint
from flask.views import MethodView
from ckan.plugins import toolkit
from ckan import model
from ckan import logic
import ckan.lib.navl.dictization_functions as dict_fns
from ckan.lib.mailer import mail_recipient
import ckan.lib.helpers as h
import logging
from ckanext.sixodp.helpers import get_current_lang

render = toolkit.render
abort = toolkit.abort
config = toolkit.config
request = toolkit.request
ValidationError = toolkit.ValidationError
NotAuthorized = toolkit.NotAuthorized
_ = toolkit._
redirect_to = toolkit.redirect_to
get_action = toolkit.get_action
clean_dict = logic.clean_dict
tuplize_dict = logic.tuplize_dict
parse_params = logic.parse_params


log = logging.getLogger(__name__)

showcasesubmitter = Blueprint('showcasesubmitter', __name__)



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

def sendNewShowcaseNotifications(showcase_name):
    recipient_emails = config.get('ckanext.sixodp_showcasesubmit.recipient_emails').split(' ')
    showcase_url = config.get('ckan.site_url') + h.url_for('showcase.read', id=showcase_name)

    message_body = _('A user has submitted a new showcase') + ': ' + showcase_url

    for email in recipient_emails:
        mail_recipient("", email, _('New showcase notification'), message_body)


class ShowcaseSubmitterView(MethodView):
    def get(self):
        extra_vars = {'data': {}, 'errors': {}, 'error_summary': {}, 'message': None}
        return render('sixodp_showcasesubmit/base_form_page.html', extra_vars=extra_vars)

    def post(self):
        data_dict = {}
        errors = {}
        error_summary = {}
        message = { 'class': 'success', 'text':  _('Showcase submitted successfully')}

        try:

            username = config.get('ckanext.sixodp_showcasesubmit.creating_user_username')
            user = model.User.get(username)

            context = {'model': model, 'session': model.Session,
                       'user': user.id, 'auth_user_obj': user.id,
                       'save': 'save' in request.args,
                       'keep_deletable_attributes_in_api': True}

            data_dict = clean_dict(dict_fns.unflatten(
                tuplize_dict(parse_params(request.form))))

            data_dict.update(
                clean_dict(
                    dict_fns.unflatten(
                        tuplize_dict(parse_params(
                            toolkit.request.files)))))


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
                        new_showcase['notes_translated']['fi'] = new_showcase.get('notes_translated', {'fi': ''}).get('fi', '') \
                                                                 + '\n\n' + _('N.B. The following dataset could not be automatically linked') + ': ' + package_name
                        get_action('ckanext_showcase_update')(context, new_showcase)

        except NotAuthorized:
            abort(403, _('Unauthorized to create a package'))
        except ValidationError as e:
            errors = e.error_dict
            error_summary = e.error_summary
            data_dict['state'] = 'none'
            message = None

        sendNewShowcaseNotifications(data_dict.get('name'))

        extra_vars = {'data': data_dict, 'errors': errors,
                'error_summary': error_summary, 'message': message}
        if errors:
            return render('sixodp_showcasesubmit/base_form_page.html', extra_vars=extra_vars)

        lang = get_current_lang()
        if lang == 'sv':
            return redirect_to('/sv/tack')
        elif lang == 'en_GB':
            return redirect_to('/en_gb/thank-you')
        else:
            return redirect_to('/kiitos')



showcasesubmitter.add_url_rule('/submit-showcase', view_func=ShowcaseSubmitterView.as_view('showcasesubmitter'),
                               strict_slashes=False)




