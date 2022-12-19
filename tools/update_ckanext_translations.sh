#!/bin/bash
#
# bash strict mode
set -euo pipefail
IFS=$'\n\t'

# A script for pulling ckan extension translations from Transifex and compiling the files to .mo files

EXTENSIONS_TO_UPDATE=(
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
  statistics
)

HELPTEXT="Usage: $0 \n\n\
Pulls ckan extension translation files from Transifex and compiles the files to .mo -files.\n\nThe script must be run
in the tools directory with an active CKAN virtual environment. Extensions can be defined in the EXTENSIONS_TO_UPDATE
variable in this script file."

function compile_translations_for_extension {
  cd ../ckanext/ckanext-$1
  echo -e "Pulling translations for ckanext-$1"
  tx pull -a -f
  echo -e "Compiling translations for ckanext-$1"
  python setup.py compile_catalog -f
  cd ../../tools
  echo -e "Translations compiled for ckanext-$1"
}

function update_translations {
  for extension in ${EXTENSIONS_TO_UPDATE[@]}
  do
    compile_translations_for_extension $extension
  done

  exit 0
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
  update_translations
fi