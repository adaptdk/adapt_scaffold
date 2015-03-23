drush situs-build --root=htdocs --make-file=./platform.make --git-check --git-check-ignore-regex=/global/,/contrib/,/libraries/

if pushd "htdocs/sites/all" > /dev/null; then
  if [ ! -L "drush" ]; then
    ln -s ../../../drush .
    echo "Symlink created for drush."
  fi
  popd > /dev/null;
fi
