# Adapt basetheme

[![forthebadge](http://forthebadge.com/images/badges/fuck-it-ship-it.svg)](http://forthebadge.com)
[![forthebadge](http://forthebadge.com/images/badges/reading-6th-grade-level.svg)](http://forthebadge.com)
[![forthebadge](http://forthebadge.com/images/badges/made-with-crayons.svg)](http://forthebadge.com)
[![forthebadge](http://forthebadge.com/images/badges/built-with-love.svg)](http://forthebadge.com)


### About the frameworks
___
###### Short about bourbon and neat
- Bourbon is a mixin library for pre-defined and most common used mixins
- Neat is a grid system for Bourbon.
- Documentation and examples on bourbon: http://bourbon.io/
- Documentation and examples on neat: http://neat.bourbon.io/

###### Short about compass & singularity
- Compass is an authoring framework
- Singularity is a grid system for compass
- Documentation and examples on compass:
http://compass-style.org/help/
- Documentation and examples on singularity
https://github.com/at-import/Singularity/wiki

#### Automated bash installing frameworks
___
When initiating a new project you can pick from two frameworks.
Bash script included will setup a Gemfile, pre-defined versions of framework selected with basic settings files.

1. Type in terminal
```bash
./fe_setup.sh [bourbon or compass]
```
2. Remove fe_setup.sh after.

#### Manually install
___
When manually installing frameworks like either a newer version of bourbon and neat, or compass and singularity

##### Manually install bourbon + neat.
Installing bourbon and neat manually.
1. First install bourbon and neat gems
```bash
gem install bourbon neat
```
2. Browse to your sass directory and type
```bash
bourbon install
neat install
```
3. Create your main stylesheet and copy + paste.
```scss
@import "bourbon/bourbon";
@import "neat/neat";
```

##### Manually install compass + singularity.
steps for compass singularity
1. First install compass and singularity gems
```bash
gem install compass singularity
```
2. Browse to your theme's root directory
```bash
compass install compass --sass-dir "sass" --css-dir "css" --javascripts-dir "js" --images-dir "img"
```

#### Gemfiles
___
Description and how to use gemfiles.

#### Architecture
___

##### Resets
Never use reset.css library or any wildcard * margin/padding resets, It's to aggressive and unpredictable. Use normalize.css which stabilises browser consistencies and quirks instead.

##### CSS Architecture
The preprocessor of choice is SCSS (Sassy SASS) and not SASS. SMACCS style so our stylesheets are more readable and structuring them after the guidelines from SMACSS. Breaking your styles into more stylesheets is encouraged.

##### Folder Architecture
Folder structure is based on categorization of SMACCS, but also of roughly the most common scenarios. This gives a sense of logical organization for when working with Drupal.

+ **base**
Variables, mixins, basic or most common single element selectors body, p, a, h1, h2, h3
+ **layout**
Larger regions of layout like header, footer, sidebars, reusable design patterns like column lists etc.
+ **modules**
Reusable elements like buttons, forms, elements, or widget components.
+ **vendor**
Contributed CSS libraries from either jquery plugins, or animations libraries

Create your own folders additionally to your needs depending / based on project.
