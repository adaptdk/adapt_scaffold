#!/bin/sh
mysqladmin -uroot -proot create test_local
mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON {{ database }}.* TO '{{ username }}'@'{{ hostname }}' IDENTIFIED BY '{{ password }}'"

if pushd "htdocs/sites/default" > /dev/null; then
  ln -s local.settings.php settings.php
  mkdir files
  chown www:everyone files
  chmod 775 files
fi