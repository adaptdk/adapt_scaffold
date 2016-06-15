#!/usr/bin/env bash

php includes/github_status.php
if [ $? -eq 1 ];
then
  echo "Aborting build process because of Github problems. Follow process here: https://status.github.com"
  exit 1;
fi

drush situs-build --root=htdocs --make-file=./platform.make --git-check --git-check-ignore-regex=/global/,/contrib/,/libraries/

if pushd "htdocs/sites/all" > /dev/null; then
  if [ ! -L "drush" ]; then
    ln -s ../../../drush .
    echo "Symlink created for drush."
  fi
  popd > /dev/null;
fi

if pushd "htdocs/profiles/{{ profile }}" > /dev/null; then
  if [ -f "composer.json" ]; then
    composer install --ignore-platform-reqs
  fi
  popd > /dev/null;
fi


if pushd "htdocs/profiles/{{ profile }}/themes/custom/{{ profile }}_theme/.npm/" > /dev/null; then
  npm install -q
  popd > /dev/null;
fi
