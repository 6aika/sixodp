from ckan.plugins.toolkit import config

def get_datasubmitter_recaptcha_sitekey():
    return config.get('ckanext.datasubmitter.recaptcha_sitekey')