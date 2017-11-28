from ckan.plugins.toolkit import get_action
from ckan.logic import side_effect_free

@side_effect_free
def get_all_public_datasets(context, data_dict):
    datasets = []

    package_dicts = get_action('package_search')({}, {"rows": 1000})

    datasets += package_dicts['results']

    handled_datasets = 1000
    while handled_datasets < package_dicts['count']:
        package_dicts = get_action('package_search')({}, {"rows": 1000, "start": handled_datasets})
        datasets += package_dicts['results']
        handled_datasets += 1000

    return datasets