import ckan.lib.navl.dictization_functions as df
from ckan.common import _


def lower_if_exists(s):
    return s.lower() if s else s


def upper_if_exists(s):
    return s.upper() if s else s


def tag_string_or_tags_required(key, data, errors, context):
    value = data.get(key)
    if not value or value is df.missing:
        data.pop(key, None)
        # Check existence of tags
        if any(k[0] == 'tags' for k in data):
                raise df.StopOnError
        else:
            errors[key].append((_('Missing value')))
            raise df.StopOnError
