import factory

from ckan.tests.factories import Dataset

from faker import Faker

fake = Faker()

class SixodpDataset(Dataset):
    title_translated = factory.Dict({
        'fi': factory.LazyFunction(lambda: fake.sentence(nb_words=5))
    })