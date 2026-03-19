import pytest

from ckan.tests.factories import Organization, User
from .factories import SixodpDataset, SixodpGroup
from ckan.tests.helpers import call_action

from ckan.plugins import toolkit
from ckan.lib.helpers import url_for

from ckanext.sixodp.helpers import get_package_groups_by_type

@pytest.mark.usefixtures('with_plugins', 'clean_db', 'clean_index')
class TestSixodpPlugin():
    def test_mandatory_values_in_dataset(self):
        user = User()
        dataset = vars(SixodpDataset.stub())
        result = call_action('package_create', context={"user": user['name']}, **dataset)

        assert result['title'] == dataset['title_translated']['fi']
        assert result['notes'] == dataset['notes_translated']['fi']
        assert result['date_released'] == dataset['date_released']
        assert result['geographical_coverage'] == dataset['geographical_coverage']
        assert result['keywords']['fi'] == dataset['keywords']['fi']
        assert result['license_id'] == dataset['license_id']
        assert result['maintainer_email'] == dataset['maintainer_email']

    def test_mandatory_values_are_required(self):
        with pytest.raises(toolkit.ValidationError) as exc_info:
            call_action('package_create')

        expected = {
            'title_translated': ['Required language "fi" missing'],
            'notes_translated': ['Required language "fi" missing'],
            'date_released': ['Missing value'],
            'keywords': ['Required language "fi" missing'],
            'license_id': ['Missing value'],
            'geographical_coverage': ['Missing value'],
            'maintainer_email': ['Missing value'],
            'name': ['Missing value']
        }
        assert exc_info.value.error_dict == expected

    def test_translated_facets_in_dataset_search(self, app):
        group = SixodpGroup()
        SixodpDataset(groups=[{'id': group['id']}])

        resp = app.get('/dataset')
        assert group['title_translated']['fi'] in resp

        resp_en = app.get('/en_GB/dataset')
        assert group['title_translated']['en'] in resp_en

        resp_sv = app.get('/sv/dataset')
        assert group['title_translated']['sv'] in resp_sv

    def test_translated_facet_in_group_search(self, app):
        group = SixodpGroup()
        SixodpDataset(groups=[{'id': group['id']}])

        resp = app.get(url=url_for("group.read", id=group["name"]))
        assert group['title_translated']['fi'] in resp

        resp_en = app.get(url=url_for("group.read", id=group["name"], locale='en_GB'))
        assert group['title_translated']['en'] in resp_en

        resp_sv = app.get(url=url_for("group.read", id=group["name"], locale='sv'))
        assert group['title_translated']['sv'] in resp_sv

    def test_translated_facet_in_organization_search(self, app):
        org = Organization()
        group = SixodpGroup()
        SixodpDataset(groups=[{'id': group['id']}], owner_org=org['id'])

        resp = app.get(url=url_for("organization.read", id=org["name"]))
        assert group['title_translated']['fi'] in resp

        resp_en = app.get(url=url_for("organization.read", id=org["name"], locale='en_GB'))
        assert group['title_translated']['en'] in resp_en

        resp_sv = app.get(url=url_for("organization.read", id=org["name"], locale='sv'))
        assert group['title_translated']['sv'] in resp_sv


    def test_helper_get_package_groups_by_type(self):
        group1 = SixodpGroup()
        group2 = SixodpGroup()
        dataset = SixodpDataset(groups=[{'id': group1['id']}])

        package_groups = get_package_groups_by_type(dataset['id'], group_type='group')
        assert len(package_groups) == 1
        assert package_groups[0]['id'] == group1['id']