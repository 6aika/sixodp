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

        if isinstance(data[key], basestring):
            tags = [tag.strip() \
                    for tag in data[key].split(',') \
                    if tag.strip()]
        else:
            tags = data[key]

        current_index = max( [int(k[1]) for k in data.keys() if len(k) == 3 and k[0] == 'tags'] + [-1 ])

        v = get_action('vocabulary_show')(context, {'id': vocab})
        if not v:
            v = plugin.create_vocabulary(vocab)

        context['vocabulary'] = model.Vocabulary.get(v.get('id'))

        for tag in tags:
            try:
                validators.tag_in_vocabulary_validator(tag, context)
            except Invalid:
               plugin.create_tag_to_vocabulary(tag, vocab)


        data[key] = json.dumps(tags)

    return callable


