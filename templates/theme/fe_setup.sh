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
    sed -i -e '/grid-settings/d' screen.scss

    sed -i -e '/compass/d' editor.scss
    sed -i -e '/singularitygs/d' editor.scss
    sed -i -e '/grid-settings/d' editor.scss

    rm -rf screen.scss-e
    rm -rf editor.scss-e

    # Install gems
    sudo gem install bourbon -v 3.2.4
    sudo gem install neat -v 1.7.0

    # Install
    bourbon install
    neat install

    # Make Gemfile
    rm -rf ../Gemfile
    printf 'source "https://rubygems.org"

gem "bourbon", "3.2.4"
gem "neat", "1.7.0"' >> ../Gemfile

    echo "Added Gemfile";

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

    if ! grep -q "@import 'grid-settings';" screen.scss ; then
      echo "@import 'grid-settings';" | cat - screen.scss > temp && mv temp screen.scss
      echo "Added grid-settings to screen.scss"
    else
      echo "@import 'grid-settings'; is already added to screen.scss";
    fi
    if ! grep -q "@import 'grid-settings';" editor.scss ; then
      echo "@import 'grid-settings';" | cat - editor.scss > temp && mv temp editor.scss
      echo "Added grid-settings to editor.scss"
    else
      echo "@import 'grid-settings'; is already added to editor.scss";
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

    rm -rf _grid-settings.scss
    if [ ! -f _grid-settings.scss ]; then

      printf '@import "neat/neat-helpers";

$column: 60px;
$gutter: 20px;
$grid-columns: 12;
$max-width: 960px;
//$visual-grid: true;

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
    sed -i -e '/grid-settings/d' screen.scss

    sed -i -e '/neat/d' editor.scss
    sed -i -e '/bourbon/d' editor.scss
    sed -i -e '/grid-settings/d' editor.scss

    rm -rf screen.scss-e
    rm -rf editor.scss-e

    # Install gems
    sudo gem install compass -v 1.0.3
    sudo gem install singularitygs -v 1.5.1

    # Make Gemfile
    rm -rf ../Gemfile
    printf 'source "https://rubygems.org"

gem "compass", "1.0.3"
gem "singularitygs", "1.5.1"' >> ../Gemfile

    echo "Added Gemfile";

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

    if ! grep -q "@import 'grid-settings';" screen.scss ; then
      echo "@import 'grid-settings';" | cat - screen.scss > temp && mv temp screen.scss
      echo "Added grid-settings to screen.scss"
    else
      echo "@import 'grid-settings'; is already added to screen.scss";
    fi
    if ! grep -q "@import 'grid-settings';" editor.scss ; then
      echo "@import 'grid-settings';" | cat - editor.scss > temp && mv temp editor.scss
      echo "Added grid-settings to editor.scss"
    else
      echo "@import 'grid-settings'; is already added to editor.scss";
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

    rm -rf _grid-settings.scss
    if [ ! -f _grid-settings.scss ]; then

      printf '$grids: 12;
$gutters-in-px: 20; //px
$gutters: $gutters-in-px/(($width - (11*$gutters-in-px))/$grids);' >> _grid-settings.scss

      echo "Created _grid-settings.scss file"

    fi

    echo -e "🍌 🍌  ${green}Compass and singularitys is successfully installed${NC} 🍌 🍌"

  else

    echo -e "${red}No argument found - choose between 'bourbon' and 'compass'${NC}"

  fi

fi
