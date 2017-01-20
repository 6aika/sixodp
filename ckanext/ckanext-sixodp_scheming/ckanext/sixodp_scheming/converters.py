import ckan.model as model
import ckan.logic.validators as validators
import ckan.lib.navl.dictization_functions as df
from itertools import count

Invalid = df.Invalid

import plugin


def convert_and_create_tags(vocab):
    def callable(key, data, errors, context):
        print data

        if isinstance(data[key], basestring):
            tags = [tag.strip() \
                    for tag in data[key].split(',') \
                    if tag.strip()]
        else:
            tags = data[key]

        current_index = max( [int(k[1]) for k in data.keys() if len(k) == 3 and k[0] == 'tags'] + [-1 ])

        v = model.Vocabulary.get(vocab)
        if not v:
            v = plugin.create_vocabulary(vocab)
        context['vocabulary'] = v

        for tag in tags:
            try:
                validators.tag_in_vocabulary_validator(tag, context)
            except Invalid:
                plugin.create_tag_to_vocabulary(tag, vocab)

        for num, tag in zip(count(current_index+1), tags):
            print num
            print tag
            print str(vocab)
            data[(str(vocab), num, 'name')] = tag
            data[(str(vocab), num, 'vocabulary_id')] = v.id
    return callable


