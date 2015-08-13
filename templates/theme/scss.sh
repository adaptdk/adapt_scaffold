#!/bin/bash
node-sass \
  --include-path node_modules/node-bourbon/node_modules/bourbon/app/assets/stylesheets \
  --include-path node_modules/node-neat/node_modules/bourbon-neat/app/assets/stylesheets \
  -w  \
  --source-map true \
  sass/ -o css \
