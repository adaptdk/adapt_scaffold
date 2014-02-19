#!/bin/bash
mysql -uroot -proot -h 127.0.0.1 -e "create database {{ database }}"
mysql -uroot -proot -h 127.0.0.1 -e "GRANT ALL PRIVILEGES ON {{ database }}.* TO '{{ username }}'@'{{ hostname }}' IDENTIFIED BY '{{ password }}'"

if pushd "htdocs/sites/default" > /dev/null; then
  ln -s local.settings.php settings.php
  mkdir files
  sudo chown www:everyone files
  sudo chmod 775 files
fi
