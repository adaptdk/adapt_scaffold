<?php

$databases = array();
$databases['default']['default'] = array(
  'driver' => 'mysql',
  'database' => '{{ database }}',
  'username' => '{{ username  }}',
  'password' => '{{ password  }}',
  'host' => '{{ hostname }}',
  'prefix' => '',
);

$conf['current_environment'] = '{{ env }}';

// Define temporary files directory
$conf['file_temporary_path'] = 'sites/default/files/tmp';

{% if env == 'live' or env == 'prod' or env == 'test' %}
// Preproccesing of js/css
$conf['preprocess_css'] = 1;
$conf['preprocess_js'] = 1;

// Caching
$conf['block_cache'] = 1;
$conf['cache'] = 1;

// Error reporting
$conf['error_level'] = 0;
{% endif %}

{% if env == 'local' %}
$conf['stage_file_proxy_origin'] = 'http://{{ domains.stage }}';
$conf['stage_file_proxy_origin_dir'] = 'sites/default/files';
{% endif %}

{% if prime.enabled == TRUE %}
// Adapt monitoring settings. Used to serve site state to our monitoring platform.
$conf['adapt_status_remote_endpoint'] = '{{ prime.tgt }}';
$conf['adapt_status_shared_secret'] = '{{ prime.ss }}';
$conf['adapt_status_enabled'] = 1;
$conf['adapt_status_site_key'] = '{{ prime.key }}';
{% endif %}

// Disable the possibility to export menu links to features
$conf['features_admin_show_component_menu_links'] = 0;
// If you need to deploy menu links use either update hooks,
// deploy or defaultconfig.

$update_free_access = FALSE;
$drupal_hash_salt = '';
# $base_url = 'http://www.example.com';  // NO trailing slash!
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_lifetime', 2000000);
# ini_set('pcre.backtrack_limit', 200000);
# ini_set('pcre.recursion_limit', 200000);
# $cookie_domain = '.example.com';
# $conf['site_name'] = 'My Drupal site';
# $conf['theme_default'] = 'garland';
# $conf['anonymous'] = 'Visitor';
# $conf['maintenance_theme'] = 'bartik';
# $conf['reverse_proxy'] = TRUE;
# $conf['reverse_proxy_addresses'] = array('a.b.c.d', ...);
# $conf['reverse_proxy_header'] = 'HTTP_X_CLUSTER_CLIENT_IP';
# $conf['omit_vary_cookie'] = TRUE;
# $conf['css_gzip_compression'] = FALSE;
# $conf['js_gzip_compression'] = FALSE;
# $conf['locale_custom_strings_en'][''] = array(
#   'forum'      => 'Discussion board',
#   '@count min' => '@count minutes',
# );
# $conf['blocked_ips'] = array(
#   'a.b.c.d',
# );
$conf['404_fast_paths_exclude'] = '/\/(?:styles)\//';
$conf['404_fast_paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$conf['404_fast_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';
# drupal_fast_404();
# $conf['proxy_server'] = '';
# $conf['proxy_port'] = 8080;
# $conf['proxy_username'] = '';
# $conf['proxy_password'] = '';
# $conf['proxy_user_agent'] = '';
# $conf['proxy_exceptions'] = array('127.0.0.1', 'localhost');
# $conf['allow_authorize_operations'] = FALSE;

$conf['syslog_identity'] = '{{ profile }}';

// Make memcache the default memcache backend.
$memcache_backend = 'profiles/{{ profile }}/modules/contrib/memcache/memcache.inc';
if (file_exists($memcache_backend)) {
  $conf['cache_backends'][] = $memcache_backend;
  $conf['cache_default_class'] = 'MemCacheDrupal';

  # cache_form should always use a non-volatile caching engine.
  $conf['cache_class_cache_form'] = 'DrupalDatabaseCache';

  $conf['memcache_bins'] = array('cache' => 'default');

  $conf['memcache_persistent'] = TRUE;
  $conf['memcache_key_prefix'] = '{{ profile }}';
  {% if env == 'live' or env == 'prod' or env == 'test' %}
  $conf['memcache_servers'] = array('mc01:11211' => 'default');
  {% else %}
  $conf['memcache_servers'] = array('127.0.0.1:11211' => 'default');
  {% endif %}
}

