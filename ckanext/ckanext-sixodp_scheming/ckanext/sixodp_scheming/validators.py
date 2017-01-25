import ckan.lib.navl.dictization_functions as df
from ckan.common import _
import ckan.authz as authz
import ckan.plugins.toolkit as toolkit
import collections

c = toolkit.c

def lower_if_exists(s):
    return s.lower() if s else s


def upper_if_exists(s):
    return s.upper() if s else s

def list_to_string(list):
    if isinstance(list, collections.Sequence) and not isinstance(list, basestring):
        return ','.join(list)
    return list

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


def set_private_if_not_admin(private):
    return True if not authz.is_sysadmin(c.user) else private
