'use strict';

var svg2favicons = require('svg2favicons');
var log = require('npmlog');

try {
  svg2favicons({
    src: '../images/icons/favicon/favicon.svg',
    dest: '../images/icons/favicon',
    icoPath: '../favicon.ico',

    developer: 'Adapt A/S',
    developerURL: 'http://adapt.eu/',

    iconsPath: '/profiles/{{ profile }}/themes/custom/{{ profile }}_theme/images/icons/favicon/',

    // Everything below is project specific
    appName: null,           // `string`
    appDescription: null,    // `string`
    background: null,        // `string`
    url: null                // `string`
  });
} catch (err) {
  log.info(
    'favicons',
    'Skipping favicon generation since config has missing values'
  );
}
