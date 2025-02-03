from flask import Blueprint

import ckan.model as model
from ckan.plugins.toolkit import g, request, get_action
from ckan.views.api import _finish_ok
from .validators import create_vocabulary


def tag_autocomplete():

    vocabulary_id = request.params.get('vocabulary_id', None)
    if vocabulary_id:
        create_vocabulary(vocabulary_id)

    q = request.params.get('incomplete', '')
    limit = request.params.get('limit', 10)
    tag_names = []
    if q:
        context = {'model': model, 'session': model.Session,
                   'user': g.user, 'auth_user_obj': g.userobj}

        data_dict = {'q': q, 'limit': limit}
        if vocabulary_id:
            data_dict['vocabulary_id'] = vocabulary_id

        tag_names =get_action('tag_autocomplete')(context, data_dict)

    resultSet = {
        'ResultSet': {
            'Result': [{'Name': tag} for tag in tag_names]
        }
    }
    return _finish_ok(resultSet)






sixodp = Blueprint('sixodp', __name__)
sixodp.add_url_rule('/api/2/util/tag/autocomplete', view_func=tag_autocomplete)