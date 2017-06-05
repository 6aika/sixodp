# Managing Wordpress translations

1. Generate the source language sixodp.pot file by running the following command in the theme directory (/sixodp)
```
php ./tools/makepot.php wp-theme . ./i18n/sixodp.pot
```

2. Upload the updated sixodp.pot file to Transifex by hand or with the tx client by running the following command. 
The Transifex project is configured in /.tx/config so the file should be uploaded as the correct resource
```
tx push -s
```

3. Translate the new source strings in Transifex and get the new language files by running:
```
tx pull
```

4. Compile the new language files