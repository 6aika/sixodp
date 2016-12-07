import sqlalchemy
import ckan.plugins.toolkit as toolkit
from ckanext.collection.model import CollectionMember

import logging
log = logging.getLogger(__name__)

_select = sqlalchemy.sql.select
_and_ = sqlalchemy.and_

def package_collections_list(context, data_dict):

    # get a list of collection ids associated with the package id
    collection_id_list = CollectionMember.get_collection_ids_for_package(
        data_dict['package_id'])

    collection_list = []
    if collection_id_list is not None:
        for collection_id in collection_id_list:
            collection = toolkit.get_action('group_show')(context,
                                                          {'id': collection_id})
            collection_list.append(collection)

    return collection_list