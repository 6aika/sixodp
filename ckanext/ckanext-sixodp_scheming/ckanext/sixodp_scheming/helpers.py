from ckan.plugins import toolkit
from ckan.lib.i18n import get_lang

def call_toolkit_function(fn, args, kwargs):
    return getattr(toolkit,fn)(*args, **kwargs)


def add_locale_to_source(kwargs, locale):
    copy = kwargs.copy()
    source = copy.get('data-module-source', None)
    if source:
            copy.update({'data-module-source': source + '_' + locale})
            return copy
    return copy

def get_current_lang():
    return get_lang()
