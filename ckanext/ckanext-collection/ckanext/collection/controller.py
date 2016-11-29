import ckan.plugins as p
import ckan.model as model
import ckan.logic as logic
from ckan.lib.base import h

c = p.toolkit.c
flatten_to_string_key = logic.flatten_to_string_key

class CollectionController(p.toolkit.BaseController):

    def search_collection(self):

        return p.toolkit.render('collection/index.html')