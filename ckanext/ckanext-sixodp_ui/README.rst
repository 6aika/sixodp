=============
ckanext-sixodp_ui
=============

---------------
Updating translations
---------------

To extract all translatable strings run this command in the plugin root directory::

    python setup.py extract_messages

After this the updated ckanext-sixodp_ui.pot with the source language can be pushed to Transifex with the transifex client.
Note that you need to set your transifex credentials into ~/.transifexrc before running the command::

    tx push --source

Translate new strings in Transifex and pull them by running::

    # --force can be added if old translations can be overwritten by the ones fetched from transifex (this is usually the case)
    tx pull
