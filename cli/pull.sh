#!/bin/bash

# Path to this script's directory
DIR=$(cd `dirname $0` && pwd)

cd ${DIR}/..
git pull --ff-only && composer install --dev
if [ $? -eq 0 ];     then

    rm -rf temp/cache/*
fi