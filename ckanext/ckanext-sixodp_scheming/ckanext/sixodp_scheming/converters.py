import ckan.model as model
import ckan.logic.validators as validators
import ckan.lib.navl.dictization_functions as df
from itertools import count
import json
from ckan.logic import get_action

Invalid = df.Invalid

import plugin


def convert_and_create_tags(vocab):
    def callable(key, data, errors, context):

        dict_with_langs = {}
        value = data[key]
        print value
        try:
            dict_with_langs = json.loads(value)

        except (ValueError, TypeError):
            pass


        if len(dict_with_langs) is not 0:
            for lang in dict_with_langs:
                add_to_vocab(context, dict_with_langs[lang], vocab + '_' + lang)
        else:
            if isinstance(value, basestring):
                tags = [tag.strip() \
                        for tag in value.split(',') \
                        if tag.strip()]
            else:
                tags = value
            add_to_vocab(context, tags, vocab)
            data[key] = json.dumps(tags)


    return callable

def add_to_vocab(context, tags, vocab):
    v = get_action('vocabulary_show')(context, {'id': vocab})
    if not v:
        v = plugin.create_vocabulary(vocab)

    context['vocabulary'] = model.Vocabulary.get(v.get('id'))

    for tag in tags:
        try:
            validators.tag_in_vocabulary_validator(tag, context)
        except Invalid:
            plugin.create_tag_to_vocabulary(tag, vocab)

