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

    // Everything below is project specific
    appName: null,           // `string`
    appDescription: null,    // `string`
    background: null,        // `string`
    url: null,               // `string`
    iconsPath: null          // `string`
    // iconsPath: '/profiles/<PROFILE>/themes/custom/<THEME>/images/icons/favicon/'
  });
} catch (err) {
  log.info(
    'favicons',
    'Skipping favicon generation since config has missing values'
  );
}
