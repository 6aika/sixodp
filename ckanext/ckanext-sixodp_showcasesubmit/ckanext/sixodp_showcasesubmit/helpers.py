from pylons import config

def get_showcasesubmit_recaptcha_sitekey():
    return config.get('ckanext.sixodp_showcasesubmit.recaptcha_sitekey')