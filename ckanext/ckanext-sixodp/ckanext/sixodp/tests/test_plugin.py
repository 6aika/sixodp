import pytest

from .factories import SixodpDataset
from ckan.tests.helpers import call_action

from ckan.plugins import toolkit

@pytest.mark.usefixtures('with_plugins', 'clean_db', 'clean_index')
class TestSixodpPlugin():
    def test_mandatory_values_in_dataset(self):
        dataset = vars(SixodpDataset.stub())
        result = call_action('package_create', **dataset)

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