#Adapt scaffold

You will need the situs plugin for drush :

    drush dl drush_situs
 
Requires at least version 1.1 of drush_situs, if you have an old version you can update by doing the following.

    rm -rf ~/.drush/drush_situs
    drush cc drush
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

Remember to keep your adapt_scaffold directory up2date

    git pull

### Local
    
By default the local git repositories are made in ```/tmp/git```, you can change this in the config.json file.

    scaffold create-local-repository <client-dir>
    
## Create the client-dir
Creates the starting structure of the platform and profile repositories.

Before you run this you should have setup the 2 required git repositories either in a local directory or at a remote repository.

    scaffold create-client-dir <client-dir>

Notice: naming of client-dir should follow naming principles described in at http://www.php.net/manual/en/functions.user-defined.php a.k.a. be a valid function name.

## Checkout of your new project/clientdir/repositories
Check the platform repo out as ```CHECKOUT_FOLDER_NAME```.

    git clone file:///tmp/git/<client-dir>_platform.git CHECKOUT_FOLDER_NAME
    cd CHECKOUT_FOLDER_NAME
    
Notice: if your using the parrot vagrant box you should replace ```CHECKOUT_FOLDER_NAME``` with the dev domain of the project.

### Build
First we build the htdocs folder so that we actually download drupal core and the initial profile including all contrib modules.

    ./build.sh

### Create local database
You can create the database and symlinking the settings.php into the ```sites/default``` folder

    ./local_setup.sh

### Install
Here we execute the ```drush site-install``` command and set the ```cron_key``` variable

    ./install.sh

