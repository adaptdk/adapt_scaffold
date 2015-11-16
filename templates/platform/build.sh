#!/usr/bin/env bash
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
