def _translations():
    """ Does nothing but hints message extractor to extract missing strings. """

    _('Title')
    _('A short and descriptive title for the dataset in multiple languages. Try not to use dates when naming a dataset, since data from multiple years will usually be published as multiple resources in one dataset.')

    _('URL')
    _('An URL-address which refers to the dataset. The automatically filled option derived from the title is the best option in most cases.')

    _('Tags')
    _('Descriptive keywords or tags through which users are able to find this dataset easily through the search. The input will suggest existing keywords in the portal. New keywords should utilize ontologies such as the generic finnish ontology YSO: finto.fi/yso/fi.')

    _('Geographical Coverage')
    _('eg. tampere')
    _('Select the municipalities from which the dataset contains data.')

    _('Description')
    _('E.g. A diverse and detailed description')
    _('An universal and easy to understand, but also diverse description of the added dataset. Describe the dataset creation process, use case and possible limitations and shortcomings as well as possible.')

    _('Links to additional information concerning the dataset')
    _('You may attach external websites or other documentation which could assist in interpreting the dataset.')

    _('Organization')
    _('The organization which owns the dataset.')

    _('Source')
    _('http://example.com/dataset.json')
    _('The original author of the dataset. Can also be an external author such as Statistics Finland. The field can e.g. be used to describe a situation where the dataset is published by a single unit but it has multiple authors.')

    _('Maintainer')
    _('Joe Bloggs')
    _('The technical maintainer for the dataset. Can in practice be the maintaining unit, bureau or as an exception a single employee.')

    _('Maintainer Email')
    _('joe@example.com')
    _('The email address for the maintaining party for the dataset. Use a mailing list or other similar means to direct the message to multiple recipients.')

    _('Maintainer Website')
    _('http://www.example.com')

    _('Visibility')
    _('Private datasets will only be seen by the logged in users of the dataset\'s organization. The private status is used when preparing a new dataset for publication.')

    _('Published')
    _('The dataset publication date.')

    _('Updated')
    _('A manually maintained date which can be used to notify when the dataset has been updated.')

    _('Update Frequency')
    _('eg. every second week')
    _('The supposed update frequency for the dataset. The field will suggest similar values used in other datasets such as yearly, monthly or realtime. A new value can also be created if required.')

    _('License')

    _('Reminder date')
    _('A date when a reminder email will be sent to the system administrator reminding to check this dataset e.g. for a yearly update.')

    _('Global ID')
    _('A global id can be assigned to identify the dataset in external services.')

    _('Search Synonyms')
    _('Keywords can be provided here to improve the findability of the dataset. E.g. words from spoken language can be provided to make the dataset searchable by those words.')

    _("The <i>data license</i> you select above only applies to the contents of any "
      "resource files that you add to this dataset. By submitting this form, you "
      "agree to release the <i>metadata</i> values that you enter into the form "
      "under the <a href=\"http://opendatacommons.org/licenses/odbl/1-0/\">Open "
      "Database License</a>.")

    _('Name')
    _('A short and descriptive name for the resource.')

    _('URL')
    _('A file or url which describes the location of the desired resource file.')

    _('Size')
    _('Size of the added resouce file in bytes. Will be automatically filled when the file is uploaded.')

    _('Format')
    _('File format of the selected resource.')

    _('Description')
    _('An universal, compact and easy to understand description of the added resource.')

    _('Position coordinates')
    _('Coordinates which describe the area which the added resource is associated with.')

    _('Time series start')
    _('A moment in time after which the data is relevant.')

    _('Time series end')
    _('A moment in time after which the data is no longer relevant.')

    _('Time Series Precision')
    _('eg. 2 weeks')
    _('A string which describes the precision of the entered time series.')

    # Licenses from licenses.json
    _('Creative Commons Attribution 4.0')
    _('https://creativecommons.org/licenses/by/4.0/')

    _('CC0 1.0')
    _('https://creativecommons.org/publicdomain/zero/1.0/')

    _('Other (Open)')