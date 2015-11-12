<?php

/**
 * @file
 * Default drush aliases.drushrc.php file.
 */

/**
 * These are the default configuration so that
 * everyone can just overwrite the different settings.
 */

$aliases['loc'] = array(
  'uri' => '{{ domain.local }}',
  'root' => '/vagrant_sites/{{ domain.local }}/htdocs',
  'remote-host' => 'default',
);

$aliases['test'] = array(
  'uri' => '{{ domain.stage }}',
  'root' => '{{ htdocs.stage }}',
  'remote-host' => '{{ ssh-host.stage }}',
);

$aliases['live'] = array(
  'uri' => '{{ domain.live }}',
  'root' => '{{ htdocs.live }}',
  'remote-host' => '{{ ssh-host.live }}',
);
