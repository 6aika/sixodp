import pytest

@pytest.mark.usefixtures('clean_db', 'clean_index')
class TestSixodpPlugin():
    def test_plugin(self):
        pass