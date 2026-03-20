import factory

from ckan.tests.factories import Dataset, Group, _name

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
    geographical_coverage = [fake.pystr(min_chars=2)]
    keywords = {
        'fi': ['somekeyword']
    }
    license_id = 'cc-by-40'
    maintainer_email = factory.LazyFunction(lambda: fake.email())

class SixodpGroup(Group):
    title_translated = {
        'fi': fake.sentence(nb_words=5),
        'en': fake.sentence(nb_words=5),
        'sv': fake.sentence(nb_words=5)
    }