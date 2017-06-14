from pylons import config

def get_datasubmitter_recaptcha_sitekey():
    return config.get('ckanext.sixodp_datasubmitter.recaptcha_sitekey')