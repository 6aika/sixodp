# -*- coding: utf-8 -*-

def _translations():
    """ Does nothing but hints message extractor to extract missing strings. """
    from ckan.common import _

    # Reports
    _('Broken links')
    _('Dataset resource URLs that are found to result in errors when resolved.')
    _('Openness (Five Stars)')
    _('Datasets graded on Tim Berners Lees\' Five Stars of Openness - openly licensed,'
      ' openly accessible, structured, open format, URIs for entities, linked.')
    _('Most popular datasets')
    _('Google analytics showing top datasets with most views')
    _('Most popular resources')
    _('Google analytics showing most downloaded resources')
    _('Publisher activity')
    _('A quarterly list of datasets created and edited by a publisher.')

    _('Title')
    _('eg. A descriptive title for the dataset')
    _('A short and descriptive title for the dataset in multiple languages. Try not to use dates when naming a dataset, since data from multiple years will usually be published as multiple resources in one dataset.')

    _('URL')
    _('An URL-address which refers to the dataset. The automatically filled option derived from the title is the best option in most cases.')

    _('Tags')
    _('E.g. transport, housing, buildings')
    _('Descriptive keywords or tags through which users are able to find this dataset easily through the search. The input will suggest existing keywords in the portal. New keywords should utilize ontologies such as the generic finnish ontology YSO: finto.fi/yso/fi.')

    _('Geographical Coverage')
    _('eg. tampere')
    _('Select the municipalities from which the dataset contains data.')

    _('Description')
    _('E.g. A diverse and detailed description')
    _('eg. A detailed description')
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
    _('Maintainer email address')
    _('The email address for the maintaining party for the dataset. Use a mailing list or other similar means to direct the message to multiple recipients.')

    _('Maintainer Website')
    _('http://www.example.com')

    _('Visibility')
    _('Private datasets will only be seen by the logged in users of the dataset\'s organization. The private status is used when preparing a new dataset for publication.')

    _('Published')
    _('The resource publication date.')
    _('The dataset publication date.')

    _('Updated')
    _('The resource update date.')
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

    _('Parent')
    _('None - top level')
    _('Icon URL')
    _('My Group')


    # Translations missing from ckan
    _('Username or Email')
    _('Current Sysadmins')
    _('Promote user to Sysadmin')
    _('Promote')
    _('Sysadmin password')
    _("<p>As a sysadmin user you have full control over this CKAN instance. Proceed "
      "with care!</p> <p>For guidance on using sysadmin features, see the CKAN  <a "
      "href=\"%(docs_url)s\" target=\"_blank\" rel=\"noreferrer\">sysadmin "
      "guide</a></p>")
    _("<p><strong>Site Title:</strong> This is the title of this CKAN instance It "
      "appears in various places throughout CKAN.</p> <p><strong>Custom "
      "Stylesheet:</strong> Define an alternative main CSS file.</p> <p><strong>Site"
      " Tag Logo:</strong> This is the logo that appears in the header of all the "
      "CKAN instance templates.</p> <p><strong>About:</strong> This text will appear"
      " on this CKAN instances <a href=\"%(about_url)s\">about page</a>.</p> "
      "<p><strong>Intro Text:</strong> This text will appear on this CKAN instances "
      "<a href=\"%(home_url)s\">home page</a> as a welcome to visitors.</p> "
      "<p><strong>Custom CSS:</strong> This is a block of CSS that appears in "
      "<code>&lt;head&gt;</code> tag of every page. If you wish to customize the "
      "templates more fully we recommend <a href=\"%(docs_url)s\" target=\"_blank\" "
      "rel=\"noreferrer\">reading the documentation</a>.</p>")

    # Overridden translations not in templates
    _('Add Group')
    _('There are currently no groups for this site')
    _('Create a Group')
    _('Save Group')

