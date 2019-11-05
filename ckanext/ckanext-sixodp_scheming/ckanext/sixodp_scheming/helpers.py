from ckan.plugins import toolkit
from ckan.lib.i18n import get_lang
import ckan.lib.i18n as i18n
from ckan.common import config, c
import ckan.logic as logic
import ckan.lib.base as base
import ckan.model as model
from ckan.model.package import Package
from ckan.lib.dictization.model_dictize import group_list_dictize

import logging
get_action = toolkit.get_action
NotFound = logic.NotFound
abort = base.abort

log = logging.getLogger(__name__)

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

def get_current_date():
    import datetime
    return datetime.date.today().strftime("%d.%m.%Y")

def get_package_groups_by_type(package_id, group_type):
    context = {'model': model, 'session': model.Session,
               'for_view': True, 'use_cache': False}

    group_list = []

    data_dict = {
        'all_fields': True,
        'include_extras': True,
        'type': group_type
    }

    groups = logic.get_action('group_list')(context, data_dict)

    try:
        pkg_obj = Package.get(package_id)
        pkg_group_ids = set(group['id'] for group in group_list_dictize(pkg_obj.get_groups(group_type, None), context))
        group_list = [group
                      for group in groups if
                      group['id'] in pkg_group_ids]
    except (NotFound):
        abort(404, _('Dataset not found'))

    return group_list

_LOCALE_ALIASES = {'en_GB': 'en'}

def get_lang_prefix():
    language = i18n.get_lang()
    if language in _LOCALE_ALIASES:
        language = _LOCALE_ALIASES[language]

    return language

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


def show_qa():

    from ckan.plugins import plugin_loaded

    if plugin_loaded('qa'):
        return True

    return False


def scheming_category_list(args):
    from ckan.logic import NotFound
    # FIXME: sometimes this might return 0 categories if in development

    try:
        context = {'model': model, 'session': model.Session, 'ignore_auth': True}
        group_ids = get_action('group_list')(context, {})
    except NotFound:
        return None
    else:
        category_list = []

        # filter groups to those user is allowed to edit
        group_authz = get_action('group_list_authz')({
            'model': model, 'session': model.Session, 'user': c.user
        }, {})

        user_group_ids = set(group[u'name'] for group in group_authz)
        group_ids = [group for group in group_ids if group in user_group_ids]

        for group in group_ids:
            try:
                context = {'model': model, 'session': model.Session, 'ignore_auth': True}
                group_details = get_action('group_show')(context, {'id': group})
            except Exception as e:
                log.error(e)
                return None

            category_list.append({
                "value": group,
                "label": group_details.get('title')
            })

    return category_list


def check_group_selected(val, data):
    log.info(val)
    log.info(data)

    if filter(lambda x: x['name'] == val, data):
        return True
    return False


def get_field_from_schema(schema, field_name):

    field = next(field for field in schema.get('dataset_fields', []) if field.get('field_name') == field_name)
    return field
