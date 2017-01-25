from ckan.plugins import toolkit
from ckan.common import request, c
import urllib
import ckan.model as model
from ckan.controllers.api import ApiController

import logging

log = logging.getLogger(__name__)

from ckan.logic import NotFound

from ckanext.sixodp_scheming.plugin import create_vocabulary

class Sixodp_RoutesController(ApiController):

    # Modification to tag_autocomplete to support tag vocabularies,
    # based on original from ytp
    def tag_autocomplete(self):

        vocabulary_id = request.params.get('vocabulary_id', None)
        if vocabulary_id:
            create_vocabulary(vocabulary_id)

        q = request.str_params.get('incomplete', '')
        q = unicode(urllib.unquote(q), 'utf-8')
        limit = request.params.get('limit', 10)
        tag_names = []
        if q:
            context = {'model': model, 'session': model.Session,
                       'user': c.user, 'auth_user_obj': c.userobj}

            data_dict = {'q': q, 'limit': limit}
            if vocabulary_id:
                data_dict['vocabulary_id'] = vocabulary_id

            tag_names = toolkit.get_action('tag_autocomplete')(context, data_dict)

        resultSet = {
            'ResultSet': {
                'Result': [{'Name': tag} for tag in tag_names]
            }
        }
        return self._finish_ok(resultSet)