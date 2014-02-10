#!/bin/bash

if pushd "htdocs/sites/default" > /dev/null; then
  drush sql-drop -y
  drush site-install {{ profile }} --site-name="{{ title }}" -y
  drush vset cron_key "{{ cron_key }}"
fi
