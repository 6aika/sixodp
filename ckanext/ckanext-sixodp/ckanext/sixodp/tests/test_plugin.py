import pytest
from factories import SixodpDataset
from ckan.tests.helpers import call_action

@pytest.mark.usefixtures('clean_db', 'clean_index')
class TestSixodpPlugin():
    def test_mandatory_values_in_dataset(self):
        dataset = SixodpDataset.stub()
        result = call_action('package_create', **dataset)