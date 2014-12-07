#!/bin/bash

# Path to this script's directory
DIR=$(cd `dirname $0` && pwd)

cd ${DIR}/..
git pull --ff-only && composer install --no-dev -o
if [ $? -eq 0 ];     then

    rm -rf temp/cache/*
    grunt build
    php www/index.php orm:generate:proxies
    php www/index.php orm:schema-tool:update --dump-sql
fi