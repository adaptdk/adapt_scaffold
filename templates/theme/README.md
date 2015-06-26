# Adapt basetheme

#### Short about bourbon and neat
- Bourbon is a mixin library for pre-defined and most common used mixins
- Neat is a grid system for Bourbon.
- Documentation and examples on bourbon: http://bourbon.io/
- Documentation and examples on neat: http://neat.bourbon.io/

#### Installing bourbon and neat

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
#### npm/task runner tools
- coming -

#### Template architecture

##### Resets
Never use reset.css library or any wildcard * margin/padding resets, It's to aggressive and unpredictable. Use normalize.css which stabilises browser consistencies and quirks instead.

##### CSS Architecture
The preprocessor of choice is SCSS (Sassy SASS) and not SASS. SMACCS style so our stylesheets are more readable and structuring them after the guidelines from SMACSS. Breaking your styles into more stylesheets is encouraged.

##### Folder Architecture
Folder structure is based on categorization of SMACCS, but also of roughly the most common scenarios. This gives a sense of logical organization for when working with Drupal.

###### base
Variables, mixins, basic or most common single element selectors body, p, a, h1, h2, h3
###### layout
Larger regions of layout like header, footer, sidebars, reusable design patterns like column lists etc.
###### modules
Reusable elements like buttons, forms, elements, or widget components.

Create your own folders additionally to your needs depending / based on project.
