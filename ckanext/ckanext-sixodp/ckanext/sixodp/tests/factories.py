import factory

from ckan.tests.factories import Dataset

from faker import Faker

fake = Faker()

class SixodpDataset(Dataset):
    title_translated = {
        'fi': fake.sentence(nb_words=5)
    }
    notes_translated = {
        'fi': fake.sentence(nb_words=5)
    }
    date_released = factory.LazyFunction(fake.date)
    geographical_coverage = factory.LazyFunction(lambda: fake.words(nb=5))
    keywords = {
        'fi': ['somekeyword']
    }
    license_id = 'cc-by-40'
    maintainer_email = factory.LazyFunction(lambda: fake.email())