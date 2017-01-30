from ckan.common import _
import ckan.authz as authz
import ckan.plugins.toolkit as toolkit
import collections

import ckan.model as model
import ckan.logic.validators as validators
import ckan.lib.navl.dictization_functions as df

import json
from ckan.logic import get_action

Invalid = df.Invalid

import plugin


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

def convert_to_list(value):
    if isinstance(value, basestring):
        tags = [tag.strip().lower() \
                for tag in value.split(',') \
                if tag.strip()]
    else:
        tags = value

    return tags



def create_tags(vocab):
    def callable(key, data, errors, context):

        value = data[key]

        if isinstance(value, list):
            add_to_vocab(context, value, vocab)
            data[key] = json.dumps(value)

    return callable

def create_fluent_tags(vocab):
    def callable(key, data, errors, context):

        value = data[key]

        if isinstance(value, dict):
            for lang in value:
                add_to_vocab(context, value, vocab + '_' + lang)
            data[key] = json.dumps(value)

    return callable

def add_to_vocab(context, tags, vocab):
    v = get_action('vocabulary_show')(context, {'id': vocab})
    if not v:
        v = plugin.create_vocabulary(vocab)

    context['vocabulary'] = model.Vocabulary.get(v.get('id'))

    for tag in tags:
        validators.tag_length_validator(tag, context)
        validators.tag_name_validator(tag, context)

        try:
            validators.tag_in_vocabulary_validator(tag, context)
        except Invalid:
            plugin.create_tag_to_vocabulary(tag, vocab)


def tag_list_output(value):
    if isinstance(value, dict) or len(value) is 0:
        return value
    return json.loads(value)