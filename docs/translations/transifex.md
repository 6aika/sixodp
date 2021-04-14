# Transifex

Translations are managed in [Transifex](https://www.transifex.com/6aika-dataportal/sixodp/). Each CKAN extensions has its own resource in Transifex and in addition, wordpress has a single resource which covers all of the wordpress translations. 

Translations from codebase to Transifex and back are handled via .po files which are uploaded and downloaded with the help of [Transifex client](https://docs.transifex.com/client/introduction).

## CKAN

Translations for CKAN consist of translations of extensions and translations of the CKAN [itself](https://www.transifex.com/okfn/ckan/). If the translation shown on UI does not exist in projects transifex resources, it comes from CKAN core translations. If someone wishes to change the translation, he needs to add translated key to the extension, where it overrides the core translation. If multiple extensions have the same translated key in their .po files, all need to have the same translation.

### Generating translation files

Translations in CKAN extensions mostly follow the [official documentation](https://docs.ckan.org/en/2.8/extensions/translating-extensions.html). In summary, following commands should cover most of the cases.

```text
// Activate virtual environment
. /usr/lib/ckan/default/bin/activate

// Run rest of commands for single extension 
cd /vagrant/ckanext/ckanext-sixodp_ui/

// Extract messages for extension
python setup.py extract_messages
```

### Translating in Transifex

The newly generated translation files can be uploaded and downloaded to transifex with the following commands:

```text
// Upload to transifex
tx push -s

// After translated in transifex, download the translated files
tx pull
```

### Compiling translations

Translation files can be compiled by running following commands:

```text
// Activate virtual environment
. /usr/lib/ckan/default/bin/activate

// Run rest of commands for single extension 
cd /vagrant/ckanext/ckanext-sixodp_ui/

// Compile translation files
python setup.py compile_catalog -f
```

## WordPress

Generating translations for WordPress is done manually with [makepot.php](https://github.com/6aika/sixodp/blob/master/sixodp/tools/makepot.php). Translation tools in wp-cli cannot be used as the version is use is too old for them and it cannot be updated until automating polylang is replaced with something as polylang-cli was [abandoned](https://github.com/diggy/polylang-cli/issues/118). makepot.php assumes specific folder structure and following commands should generate translation files.

```text
// Run in vagrant home /home/vagrant
cp -r /vagrant/sixodp/ sixodp
cp -r /opt/wordpress/ src
cd sixodp/tools/
php makepot.php wp-theme ../ /vagrant/sixodp/i18n/sixodp.pot
```

After being pushed and pulled from Transifex, following commands will compile the translations files:

```text
msgfmt /vagrant/sixodp/i18n/en_GB/LC_MESSAGES/sixodp.po -o /vagrant/sixodp/i18n/en_GB/LC_MESSAGES/sixodp-en_GB.mo
msgfmt /vagrant/sixodp/i18n/fi/LC_MESSAGES/sixodp.po -o /vagrant/sixodp/i18n/fi/LC_MESSAGES/sixodp-fi.mo
msgfmt /vagrant/sixodp/i18n/sv/LC_MESSAGES/sixodp.po -o /vagrant/sixodp/i18n/sv/LC_MESSAGES/sixodp-sv.mo
```

## Other notes

There are some tools which may or may not work in [https://github.com/6aika/sixodp/tree/master/tools](https://github.com/6aika/sixodp/tree/master/tools) for CKAN and in [https://github.com/6aika/sixodp/tree/master/sixodp/tools](https://github.com/6aika/sixodp/tree/master/sixodp/tools) for WordPress.

