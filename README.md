#Adapt scaffold

You will need the situs plugin for drush :

    drush dl drush_situs
 
## Setup the scaffold program 
    
Clone the scaffold program :

    git clone https://github.com/adaptdk/adapt_scaffold.git

Build the scaffolding program :

    cd adapt_scaffold
    ./install.sh

Optionally make a symlink :
 
    ln -s <dir>/adapt_scaffold/scaffold.php /usr/bin/scaffold

## Create repository

### Local
    
By default local repositories are made in /tmp/git, you can change this is the config.json file.

    scaffold create-local-repository <client-dir>
    
## Create the client-dir

    scaffold create-client-dir hejsan
   