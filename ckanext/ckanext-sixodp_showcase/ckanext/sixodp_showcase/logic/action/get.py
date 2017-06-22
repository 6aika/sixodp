import sqlalchemy

import ckan.plugins.toolkit as toolkit
import ckan.lib.dictization.model_dictize as model_dictize
from ckan.lib.navl.dictization_functions import validate

from ckanext.showcase.logic.schema import (showcase_package_list_schema,
                                           package_showcase_list_schema)
from ckanext.showcase.model import ShowcasePackageAssociation, ShowcaseAdmin

import logging
log = logging.getLogger(__name__)

_select = sqlalchemy.sql.select
_and_ = sqlalchemy.and_


@toolkit.side_effect_free
def showcase_list(context, data_dict):
    '''Return a list of all showcases in the site.'''

    toolkit.check_access('ckanext_showcase_list', context, data_dict)

    model = context["model"]

    q = model.Session.query(model.Package) \
        .filter(model.Package.type == 'showcase') \
        .filter(model.Package.state == 'active')

    # Showcase includes private showcases by default, but those can be excluded with include_private = false
    if data_dict.get('include_private') == 'false':
        q = q.filter(model.Package.private == False)

    showcase_list = []
    for pkg in q.all():
        showcase_list.append(model_dictize.package_dictize(pkg, context))

    return showcase_list
