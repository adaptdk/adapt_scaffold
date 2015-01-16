#!/bin/bash

red='\033[0;31m'
green='\033[0;32m'
NC='\033[0m' # No Color

read -p "Are you sure (y/n)?" CONT
if [ "$CONT" == "y" ]; then

  if [ "$1" == "bourbon" ]
  then

    cd sass/

    rm -rf ../config.rb

    sed -i -e '/compass/d' screen.scss
    sed -i -e '/singularitygs/d' screen.scss

    sed -i -e '/compass/d' editor.scss
    sed -i -e '/singularitygs/d' editor.scss

    rm -rf screen.scss-e
    rm -rf editor.scss-e

    if ! gem spec bourbon > /dev/null 2>&1; then
      sudo gem install bourbon
    fi
    if ! gem spec neat > /dev/null 2>&1; then
      sudo gem install neat
    fi

    bourbon install
    neat install

    if ! grep -q "@import 'neat/neat';" screen.scss ; then
      echo "@import 'neat/neat';" | cat - screen.scss > temp && mv temp screen.scss
      echo "Added neat to screen.scss"
    else
      echo "@import 'neat/neat'; is already added to screen.scss";
    fi

    if ! grep -q "@import 'neat/neat';" editor.scss ; then
      echo "@import 'neat/neat';" | cat - editor.scss > temp && mv temp editor.scss
      echo "Added neat to editor.scss"
    else
      echo "@import 'neat/neat'; is already added to editor.scss";
    fi

    if ! grep -q "@import 'bourbon/bourbon';" screen.scss ; then
      echo "@import 'bourbon/bourbon';" | cat - screen.scss > temp && mv temp screen.scss
      echo "Added bourbon to screen.scss"
    else
      echo "@import 'bourbon/bourbon'; is already added to screen.scss";
    fi

    if ! grep -q "@import 'bourbon/bourbon';" editor.scss ; then
      echo "@import 'bourbon/bourbon';" | cat - editor.scss > temp && mv temp editor.scss
      echo "Added bourbon to editor.scss"
    else
      echo "@import 'bourbon/bourbon'; is already added to editor.scss";
    fi

    if [ ! -f _grid-settings.scss ]; then

      printf '@import "neat/neat-helpers";

  // Change the grid settings
  $column: 60px;
  $gutter: 20px;
  $grid-columns: 12;
  $max-width: 960px;
  //$visual-grid: true;

  // Define your breakpoints
  $tablet: new-breakpoint(max-width 768px 8);
  $mobile: new-breakpoint(max-width 480px 4);' >> _grid-settings.scss

      echo "Created _grid-settings.scss file"

    fi

    echo -e "🍌 🍌  ${green}Bourbon and neat is successfully installed${NC} 🍌 🍌"

  elif [ "$1" == "compass" ]
  then

    cd sass/

    rm -rf neat
    rm -rf bourbon
    rm -rf _grid-settings.scss

    sed -i -e '/neat/d' screen.scss
    sed -i -e '/bourbon/d' screen.scss

    sed -i -e '/neat/d' editor.scss
    sed -i -e '/bourbon/d' editor.scss

    rm -rf screen.scss-e
    rm -rf editor.scss-e

    if ! gem spec compass > /dev/null 2>&1; then
      sudo gem install compass
    fi
    if ! gem spec singularitygs > /dev/null 2>&1; then
      sudo gem install singularitygs
    fi

    if ! grep -q "@import 'singularitygs';" screen.scss ; then
      echo "@import 'singularitygs';" | cat - screen.scss > temp && mv temp screen.scss
      echo "Added singularitygs to screen.scss"
    else
      echo "@import 'singularitygs'; is already added to screen.scss";
    fi
    if ! grep -q "@import 'singularitygs';" editor.scss ; then
      echo "@import 'singularitygs';" | cat - editor.scss > temp && mv temp editor.scss
      echo "Added singularitygs to editor.scss"
    else
      echo "@import 'singularitygs'; is already added to editor.scss";
    fi


    if ! grep -q "@import 'compass';" screen.scss ; then
      echo "@import 'compass';" | cat - screen.scss > temp && mv temp screen.scss
      echo "Added compass to screen.scss"
    else
      echo "@import 'compass'; is already added to screen.scss";
    fi
    if ! grep -q "@import 'compass';" editor.scss ; then
      echo "@import 'compass';" | cat - editor.scss > temp && mv temp editor.scss
      echo "Added compass to editor.scss"
    else
      echo "@import 'compass'; is already added to editor.scss";
    fi

    if [ ! -f ../config.rb ]; then

      printf "# Require any additional compass plugins here.
  require 'singularitygs'

  # Set this to the root of your project when deployed:
  http_path = '/'
  css_dir = 'css'
  sass_dir = 'sass'
  images_dir = 'img'
  javascripts_dir = 'js'

  # You can select your preferred output style here (can be overridden via the command line):
  output_style = :compact

  # To enable relative paths to assets via compass helper functions. Uncomment:
  # relative_assets = true

  # To disable debugging comments that display the original location of your selectors. Uncomment:
  line_comments = false


  # If you prefer the indented syntax, you might want to regenerate this
  # project again passing --syntax sass, or you can uncomment this:
  # preferred_syntax = :sass
  # and then run:
  # sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass" >> ../config.rb

      echo "Created config.rb file"

    fi

    echo -e "🍌 🍌  ${green}Compass and singularitys is successfully installed${NC} 🍌 🍌"

  else

    echo -e "${red}No argument found - choose between 'bourbon' and 'compass'${NC}"

  fi

fi
