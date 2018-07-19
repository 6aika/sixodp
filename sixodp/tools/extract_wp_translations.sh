#!/bin/bash
#
# bash strict mode
set -euo pipefail
IFS=$'\n\t'

# A script for extracting worpdress translation keys

TRANSLATIONS_PATH="../i18n"

HELPTEXT="Usage: $0 \n\n\
Extracts Wordpress translation keys"

function extract_messages {
  # Main theme
  php makepot.php wp-theme ../ ../i18n/sixodp.pot

  # Plugins
  php makepot.php wp-theme ../../wordpress/wp-trello/ ../../wordpress/wp-trello/lang/wp-trello.pot

  echo -e Translation keys extracted successfully
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
  extract_messages "$@"
fi
