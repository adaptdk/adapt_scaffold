#!/bin/sh

REPO=$1.git
GIT_PATH=/home/tom/test

sudo -u git mkdir $GIT_PATH/$REPO
cd $GIT_PATH/$REPO
sudo -u git git --bare init

echo "--"
echo "-- Your new git repo '$REPO' is ready and initialized at:"
echo "-- $GIT_USER@githost:$GIT_PATH/$REPO"
echo "--"
