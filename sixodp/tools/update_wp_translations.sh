#!/bin/bash
#
# bash strict mode
set -euo pipefail
IFS=$'\n\t'

# A script for pulling wordpress translations from Transifex and compiling the files to .mo

TRANSLATIONS_PATH="../i18n"

HELPTEXT="Usage: $0 \n\n\
Pulls Wordpress translation files from Transifex and compiles the files to .mo.\nThe script assumes that the translations are located
in the default path relative to this script: $TRANSLATIONS_PATH/<language>/LC_MESSAGES/<language_file.po>\n"

function compile_translations {
  # Main theme
  tx pull -a -f
  msgfmt "$TRANSLATIONS_PATH/en_GB/LC_MESSAGES/sixodp.po" -o "$TRANSLATIONS_PATH/en_GB/LC_MESSAGES/sixodp-en_GB.mo"
  cp $TRANSLATIONS_PATH/en_GB/LC_MESSAGES/sixodp-en_GB.mo /opt/wordpress/wp-content/languages/themes/
  msgfmt "$TRANSLATIONS_PATH/fi/LC_MESSAGES/sixodp.po" -o "$TRANSLATIONS_PATH/fi/LC_MESSAGES/sixodp-fi.mo"
  cp $TRANSLATIONS_PATH/fi/LC_MESSAGES/sixodp-fi.mo /opt/wordpress/wp-content/languages/themes/
  msgfmt "$TRANSLATIONS_PATH/sv/LC_MESSAGES/sixodp.po" -o "$TRANSLATIONS_PATH/sv/LC_MESSAGES/sixodp-sv.mo"
  cp $TRANSLATIONS_PATH/sv/LC_MESSAGES/sixodp-sv.mo /opt/wordpress/wp-content/languages/themes/

  # Plugins
  cd "../../wordpress/wp-trello/"
  tx pull -a -f
  msgfmt "./lang/wp-trello-en_GB.po" -o "lang/wp-trello-en_GB.mo"
  msgfmt "./lang/wp-trello-fi.po" -o "lang/wp-trello-fi.mo"
  msgfmt "./lang/wp-trello-sv.po" -o "lang/wp-trello-sv.mo"

  echo -e "Translations compiled, exiting..."
}

function help_and_exit {
  echo -e $HELPTEXT
  exit 1
}

# Subcommand handling
if [ $# == 1 ]
then
  help_and_exit
else
  compile_translations "$@"
fi
