from ckanext.scheming.helpers import scheming_get_dataset_schema
from ckan.common import OrderedDict, g

import ckan.plugins as p
import ckan.plugins.toolkit as toolkit
import ckan.model as model
import json

_ = toolkit._
c = toolkit.c

class EditorController(p.toolkit.BaseController):

    def get_dataset_fields(self):
        fields = model.Package.get_fields(core_only=True)

        scheming_schema = scheming_get_dataset_schema('dataset')['dataset_fields']

        scheming_fields = []
        for field in scheming_schema:
            scheming_fields.append(field['field_name'].encode('utf8'))

        # Remove duplicate fields, since scheming can contain fields named similarly to CKAN core fields
        for field in scheming_fields:
            if field not in fields:
                fields.append(field)

        return fields


    def get_editor_form(self):
        package_type = 'dataset'
        facets = OrderedDict()

        default_facet_titles = {
                'organization': _('Organizations'),
                'groups': _('Groups'),
                'tags': _('Tags'),
                'res_format': _('Formats'),
                'license_id': _('Licenses'),
                }

        for facet in g.facets:
            if facet in default_facet_titles:
                facets[facet] = default_facet_titles[facet]
            else:
                facets[facet] = facet

        # Facet titles
        for plugin in p.PluginImplementations(p.IFacets):
            facets = plugin.dataset_facets(facets, package_type)

        c.facet_titles = facets

        c.fields = self.get_dataset_fields()

        return toolkit.render('editor/editor_form.html')


    def package_update(self):
        package_id = request.params['id']
        context = {'model': model, 'user': c.user, 'auth_user_obj': c.userobj}

        package = toolkit.get_action('package_show')(context, { 'id': package_id })

        body_dict = json.loads(request.body)
        
        for key, value in body_dict.iteritems():
            package[key] = value

        try:
            toolkit.get_action('package_update')(context, package)
            return json.dumps({ "Success" : True })
        except NotAuthorized:
            return '{"status":"Not Authorized", "message":"' + _("Access denied.") + '"}'
        except NotFound:
            return '{"status":"Not Found", "message":"' + _("Package not found.") + '"}'
        except ValidationError:
            return '{"status":"Conflict", "message":"' + _("Validation error.") + '"}'