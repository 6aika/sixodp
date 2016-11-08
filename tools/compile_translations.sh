#!/bin/bash
#
# bash strict mode
set -euo pipefail
IFS=$'\n\t'

# A script for compiling ckan-extension translations

EXTENSION_PATH="../ckanext"

HELPTEXT="Usage: $0 ckanext-yourextensionname\n\n\
Compiles ckan-extension translations.\nThe script assumes that the extension translations are located 
in the default path relative to this script: $EXTENSION_PATH/ckanext-extension_name/i18n/\n"

function compile_translations {
  CKANEXT_NAME=$1
  cd "$EXTENSION_PATH/$CKANEXT_NAME/i18n/"
  msgfmt "./en_GB/LC_MESSAGES/$CKANEXT_NAME.po" -o "./en_GB/LC_MESSAGES/$CKANEXT_NAME.mo"
  msgfmt "./fi/LC_MESSAGES/$CKANEXT_NAME.po" -o "./fi/LC_MESSAGES/$CKANEXT_NAME.mo"
  msgfmt "./sv/LC_MESSAGES/$CKANEXT_NAME.po" -o "./sv/LC_MESSAGES/$CKANEXT_NAME.mo"
  echo -e "Translations compiled, exiting..."
}

function help_and_exit {
  echo -e $HELPTEXT
  exit 1
}

# Subcommand handling
if [ $# == 1 ]
then
  compile_translations "$@"
else
  help_and_exit
fi