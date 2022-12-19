#!/bin/bash
#
# bash strict mode
set -euo pipefail
IFS=$'\n\t'

EXTENSION_PATH="../ckanext"
RESULT_FILE_PATH="concatenated.po"

HELPTEXT="Usage: $0 language_to_concat (en_GB, fi or sv)\n\n\
Concatenates .po-files into one\n
The script assumes that the extension translations are located
in the default path relative to this script: $EXTENSION_PATH/<ckanext-extension_name>/ckanext/<extension_name>/i18n/\n"

EXTENSIONS_TO_CONCAT=(
  collection
  datasetcopy
  datasubmitter
  editor
  googleanalytics
  qa
  rating
  reminder
  showcase
  sixodp_scheming
  sixodp_showcase
  sixodp_showcasesubmit
  sixodp
)

function concat_translations {
  LANGUAGE=$1

  # Wordpress translations
  cat "../sixodp/i18n/$LANGUAGE/LC_MESSAGES/sixodp.po" >> $RESULT_FILE_PATH

  # CKAN extension translations
  for extension in ${EXTENSIONS_TO_CONCAT[@]}
  do
    cat "$EXTENSION_PATH/ckanext-$extension/ckanext/$extension/i18n/$LANGUAGE/LC_MESSAGES/ckanext-$extension.po" >> $RESULT_FILE_PATH
  done

  # CKAN-core translations
  #  cat "../ansible/roles/ckan-translations/files/$LANGUAGE.po" >> $RESULT_FILE_PATH
  cat "../ansible/roles/ckan-translations/files/$LANGUAGE.po" >> $RESULT_FILE_PATH

  exit 0
}

function help_and_exit {
  echo -e $HELPTEXT
  exit 1
}

# Subcommand handling
if [ $# == 1 ]
then
  concat_translations "$@"
else
  help_and_exit
fi