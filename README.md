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
    
By default the local git repositories are made in /tmp/git, you can change this is the config.json file.

    scaffold create-local-repository <client-dir>
    
## Create the client-dir
TODO: What does this do?

    scaffold create-client-dir <client-dir>

# Checkout repository
Check the platform repo out as <client-dir> or whatever you vagrant magic want it to be called.

    git clone file:///tmp/git/<client-dir>_platform.git <client-dir>

# Create local database
You can create a local database with vagrant or this command:

    cd <client-dir-folder>
    ./local_setup.sh
    
# Build

    cd <client-dir-folder>
    ./build.sh

# Install

    cd <client-dir-folder>
    ./install.sh

