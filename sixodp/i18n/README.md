# Managing Wordpress translations

1. Generate the source language sixodp.pot file by running the following command in the wordpress tools directory (/sixodp/tools)
```
./extract_wp_translations.sh
```

2. Upload the updated sixodp.pot file to Transifex by hand or with the tx client by running the following command. 
The Transifex project is configured in /.tx/config so the file should be uploaded as the correct resource
N.B. If you wish to update translations for wp-plugins as well, you need to run the following command in each of the
plugin's folder.
```
tx push -s
```

3. Fetch and compile the new translation files
```
./update_wp_translations.sh
```