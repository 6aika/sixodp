from ckan.plugins import toolkit
from ckan.lib.i18n import get_lang
import ckan.lib.i18n as i18n
from ckan.common import config
import ckan.logic as logic
import ckan.lib.base as base
import ckan.model as model
from ckan.model.package import Package
from ckan.lib.dictization.model_dictize import group_list_dictize

NotFound = logic.NotFound
abort = base.abort

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


def scheming_field_only_default_required(field, lang):

    if field and field.get('only_default_lang_required') and lang == config.get('ckan.locale_default', 'en'):
        return True

    return False

def ensure_translated(s):
    ts = type(s)
    if ts == unicode:
        return s
    elif ts == str:
        return unicode(s)
    elif ts == dict:
        language = i18n.get_lang()
        return ensure_translated(s.get(language, u""))

def get_current_date():
    import datetime
    return datetime.date.today().strftime("%d.%m.%Y")

def get_package_groups_by_type(package_id, group_type):
    context = {'model': model, 'session': model.Session,
               'for_view': True, 'use_cache': False}

    group_list = []

    try:
        pkg_obj = Package.get(package_id)
        group_list = group_list_dictize(pkg_obj.get_groups(group_type, None), context)
    except (NotFound):
        abort(404, _('Dataset not found'))

    return group_list

_LOCALE_ALIASES = {'en_GB': 'en'}

def get_translated_or_default_locale(data_dict, field):
    language = i18n.get_lang()
    if language in _LOCALE_ALIASES:
        language = _LOCALE_ALIASES[language]

    try:
        value = data_dict[field+'_translated'][language]
        if value:
            return value
        else:
            return data_dict[field+'_translated'][config.get('ckan.locale_default', 'en')]
    except KeyError:
        return data_dict.get(field, '')