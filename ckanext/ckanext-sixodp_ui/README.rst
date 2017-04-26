=================
ckanext-sixodp_ui
=================


Managing translations
---------------------

**Creating/Updating the base language file and Transifex resource**

To extract all translatable strings in the extension run this command in the extension root directory
(NOTE that sixodp_scheming and sixodp_showcase extension schema translations are extracted from a separate translations.py file.
Any updates to the schema need to be updated to this file also before running extract_messages)::

    python setup.py extract_messages

After this the updated ckanext-sixodp_ui.pot with the source language can be pushed to Transifex as a resource with the Transifex Client. Note that you need to set your transifex credentials into ~/.transifexrc before running the command or enter your username and password when prompted::

    tx push --s

Now you can translate new strings for the resource in the `Transifex web-ui <https://www.transifex.com/6aika-dataportal>`_.

**Downloading new translations from Transifex**

Download the new language files by running this in the extension's directory::

    tx pull
    # --force -option can be added if old translations can be overwritten by
    # the ones fetched from transifex (this is usually the case)

Recompile the language files for CKAN by activating your python environment and running::

    python setup.py compile_catalog -f
    # -f is needed to since Transifex will set the exported files as fuzzy

**Troubleshooting**

* If the Transifex **resource is not found** when updating or downloading with the Client, check the resource slug in Transifex web-ui. The project's convention is to name the slug equal to the extension name. For example for ckanext-sixodp_ui the slug should be "ckanext-sixodp_ui" (not "ckanext-sixodp_uipot" to which Transifex usually defaults).

 The slug can also be found in the extensions Transifex-config in /.tx/config formatted as [<project_name>.<resource_slug>]


For more help refer to `CKAN docs for translating extensions <http://docs.ckan.org/en/ckan-2.6.0/extensions/translating-extensions.html>`_.