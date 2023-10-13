#!/usr/bin/env bash

# NAME
#     commit-code-check.sh - Run all code quality checks.
#
# SYNOPSIS
#     bash scripts/commit-code-check.sh
#
# DESCRIPTION
#     Performs the following quality checks, closely matching core's commit-code-check.sh:
#     - Spell checking.
#     - PHPCS checks PHP and YAML files.
#     - PHPStan checks PHP files.
#     - ESLint checks YAML files.

# cSpell:disable

cd "$(dirname "$0")/../" || exit;

DRUPALCI=0
while test $# -gt 0; do
  case "$1" in
    -h|--help)
      echo "Drupal code quality checks"
      echo " "
      echo "options:"
      echo "-h, --help                show brief help"
      echo "--drupalci                a special mode for DrupalCI"
      echo " "
      exit 0
      ;;
    --drupalci)
      DRUPALCI=1
      shift
      ;;
    *)
      break
      ;;
  esac
done

MODULE_DIRECTORY=$(pwd)

# Find the site root directory. Check up to three directories above.
DIR=$(pwd)
for i in {0..3}; do
  DIR=$(dirname "$DIR")
  if test -f "$DIR/core/composer.json"; then
    CORE_DIRECTORY="$DIR"
    break
  fi
done

# Set up variables to make colored output simple. Color output is disabled on
# DrupalCI because it is breaks reporting.
# @todo https://www.drupal.org/project/drupalci_testbot/issues/3181869
if [[ "$DRUPALCI" == "1" ]]; then
  red=""
  green=""
  reset=""
else
  red=$(tput setaf 1 && tput bold)
  green=$(tput setaf 2)
  title=$(tput setaf 4 && tput bold)
  reset=$(tput sgr0)
fi

# This script assumes that composer install and yarn install have already been
# run and all dependencies are updated.
FINAL_STATUS=0

print_separator() {
  printf "\n${title}"
  printf -- '-%.0s' {1..100}
  printf "${reset}\n"
}
print_title() {
  print_separator
  printf "${title}$1${reset}"
  print_separator
}
print_results() {
  RESULTS=$1
  LABEL=$2
  if [ "$RESULTS" -ne "0" ]; then
    # If there are failures set the status to a number other than 0.
    FINAL_STATUS=1
    printf "\n${title}$LABEL: ${red}failed${reset}\n"
  else
    printf "\n${title}$LABEL: ${green}passed${reset}\n"
  fi
}

print_title "[1/5] PHPCS"
$CORE_DIRECTORY/vendor/bin/phpcs $MODULE_DIRECTORY -ps --standard="$CORE_DIRECTORY/core/phpcs.xml.dist"
print_results $? "PHPCS"

print_title "[2/5] PHPStan"
php -d apc.enabled=0 -d apc.enable_cli=0 $CORE_DIRECTORY/vendor/bin/phpstan analyze --no-progress --configuration="$MODULE_DIRECTORY/phpstan.neon.dist" $MODULE_DIRECTORY
print_results $? "PHPStan"

print_title "[3/5] CSpell"
cd $CORE_DIRECTORY/core && yarn run -s spellcheck --no-progress --root $MODULE_DIRECTORY -c .cspell.json "**" && cd -
print_results $? "CSpell"

print_title "[4/5] eslint:svelte"
cd $MODULE_DIRECTORY/sveltejs && yarn install && yarn lint:svelte
print_results $? "eslint:svelte"
cd -

print_title "[5/5] Svelte compile check"
cd $MODULE_DIRECTORY/sveltejs && cp -R public old_public && yarn run rollup -c && diff -r public old_public > /dev/null 2>&1; NOCHANGES=$?; rm -rf old_public
cd -
if [ "$NOCHANGES" -ne 0 ]; then
  FINAL_STATUS=1
  printf "\nSvelte ${red}compiled files do not match${reset}. Running yarn build and commiting the changs should fix this. \n"
else
  printf "\nSvelte compiling ${green}successful${reset}\n"
fi
print_separator

if [[ "$FINAL_STATUS" == "1" ]]; then
  printf "${red}Drupal code quality checks failed.${reset}"
  if [[ "$DRUPALCI" == "1" ]]; then
    printf "\nTo reproduce this output locally:\n"
    printf "* Apply the change as a patch, or from the merge request branch\n"
    printf "* Run this command locally: sh ./scripts/commit-code-check.sh"
  fi
  print_separator
fi
exit $FINAL_STATUS
