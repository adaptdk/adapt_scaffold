<?php

/**
 * @file
 * Default drush aliases.drushrc.php file.
 */

/**
 * These are the default configuration so that
 * everyone can just overwrite the different settings.
 */

$aliases['{{ name }}.loc'] = array(
  'uri' => '{{ domain.local }}',
  'root' => str_replace('drush/aliases', 'docroot', dirname(__FILE__)),
);

$aliases['{{ name }}.stage'] = array(
  'uri' => '{{ domain.stage }}',
  'root' => '{{ htdocs.stage }}',
  'remote-host' => '{{ ssh-host.stage }}',
);

$aliases['{{ name }}.live'] = array(
  'uri' => '{{ domain.live }}',
  'root' => '{{ htdocs.live }}',
  'remote-host' => '{{ ssh-host.live }}',
);
