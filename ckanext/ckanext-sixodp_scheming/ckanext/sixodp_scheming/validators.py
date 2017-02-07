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

ObjectNotFound = toolkit.ObjectNotFound
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
    try:
        v = get_action('vocabulary_show')(context, {'id': vocab})
    except ObjectNotFound:
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

def repeating_text(key, data, errors, context):
    """
    Accept repeating text input in the following forms
    and convert to a json list for storage:

    1. a list of strings, eg.

       ["Person One", "Person Two"]

    2. a single string value to allow single text fields to be
       migrated to repeating text

       "Person One"

    3. separate fields per language (for form submissions):

       fieldname-0 = "Person One"
       fieldname-1 = "Person Two"
    """
    # just in case there was an error before our validator,
    # bail out here because our errors won't be useful
    if errors[key]:
        return

    value = data[key]
    # 1. list of strings or 2. single string
    if value is not toolkit.missing:
        if isinstance(value, basestring):
            value = [value]
        if not isinstance(value, list):
            errors[key].append(_('expecting list of strings'))
            return

        out = []
        for element in value:
            if not isinstance(element, basestring):
                errors[key].append(_('invalid type for repeating text: %r')
                                   % element)
                continue
            if isinstance(element, str):
                try:
                    element = element.decode('utf-8')
                except UnicodeDecodeError:
                    errors[key]. append(_('invalid encoding for "%s" value')
                                        % toolkit.lang)
                    continue
            out.append(element)

        if not errors[key]:
            data[key] = json.dumps(out)
        return

    # 3. separate fields
    found = {}
    prefix = key[-1] + '-'
    extras = data.get(key[:-1] + ('__extras',), {})

    for name, text in extras.iteritems():
        if not name.startswith(prefix):
            continue
        if not text:
            continue
        index = name.split('-', 1)[1]
        try:
            index = int(index)
        except ValueError:
            continue
        found[index] = text

    out = [found[i] for i in sorted(found)]
    data[key] = json.dumps(out)

def repeating_text_output(value):
    """
    Return stored json representation as a list, if
    value is already a list just pass it through.
    """
    if isinstance(value, list):
        return value
    if value is None:
        return []
    try:
        return json.loads(value)
    except ValueError:
        return [value]