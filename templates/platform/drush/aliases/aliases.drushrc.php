<?php

/**
 * @file
 * Default drush aliases.drushrc.php file.
 */

/**
 * These are the default configuration so that
 * everyone can just overwrite the different settings.
 */

$user_home_dir = $_SERVER['HOME'];
$vagrant_dir = (isset($vagrant_dir)) ? $vagrant_dir : "$user_home_dir/Workspace/parrot";
$vagrant = [
  "-p",
  "2222",
  "-o",
  "Compression=yes",
  "-o",
  "DSAAuthentication=yes",
  "-o",
  "LogLevel=FATAL",
  "-o",
  "StrictHostKeyChecking=no",
  "-o",
  "UserKnownHostsFile=/dev/null",
  "-o",
  "IdentitiesOnly=yes",
  "-i",
  "$vagrant_dir/.vagrant/machines/default/virtualbox/private_key",
  "-o",
  "ForwardAgent=yes"
];

$aliases['local'] = array(
  'uri' => '{{ domain.local }}',
  'root' => $user_home_dir . '/Sites/{{ domain.local }}/htdocs',
);
$aliases['loc'] = [
  'parent' => '@local',
];
$aliases['l'] = [
  'parent' => '@local',
];
$aliases['vagrant'] = [
  'uri' => '{{ domain.local }}',
  'root' => '/vagrant_sites/{{ domain.local }}/htdocs',
  'remote-host' => 'default',
//  'remote-user' => 'vagrant',
//  'remote-host' => "127.0.0.1",
//  'ssh-options' => implode(' ', $vagrant),
];
$aliases['v'] = [
  'parent' => '@vagrant',
];

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
