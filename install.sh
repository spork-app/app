#!/usr/bin/env bash

cp .env.example .env

PUSHER_APP_ID=$(cat /proc/sys/kernel/random/uuid | sed 's/[-]//g' | head -c 8)
PUSHER_APP_KEY=$(cat /proc/sys/kernel/random/uuid | sed 's/[-]//g' | head -c 10)
PUSHER_APP_SECRET=$(cat /proc/sys/kernel/random/uuid | sed 's/[-]//g' | head -c 20)

sed -i "s/PUSHER_APP_ID=/PUSHER_APP_ID=$PUSHER_APP_ID/g" .env
sed -i "s/PUSHER_APP_KEY=/PUSHER_APP_KEY=$PUSHER_APP_KEY/g" .env
sed -i "s/PUSHER_APP_SECRET=/PUSHER_APP_SECRET=$PUSHER_APP_SECRET/g" .env

# Install composer
if [ -f `which docker` ]; then 
    docker run --rm -v "$(pwd)":/opt -w /opt laravelsail/php81-composer:latest bash -c "composer install"
else
    composer install
fi

if [[ "$(which sail)" = "" ]]; then
    export alias sail="$(pwd)/vendor/bin/sail"
    echo 'alias sail="$(pwd)/vendor/bin/sail"' >> ~/.bashrc
fi

vendor/bin/sail up -d

vendor/bin/sail artisan key:generate

vendor/bin/sail artisan install
# "spork/analytics": "dev-main",
# "spork/calendar": "dev-main",
# "spork/development": "dev-main",
# "spork/finance": "dev-main",
# "spork/food": "dev-main",
# "spork/greenhouse": "dev-main",
# "spork/maintenance": "dev-main",
# "spork/news": "dev-main",
# "spork/planning": "dev-main",
# "spork/research": "dev-main",
# "spork/shopping": "dev-main",
# "spork/weather": "dev-main",
# "spork/wiretap": "dev-main",


# {
#     "type": "path",
#     "url": "./system/analytics"
# },
# {
#     "type": "path",
#     "url": "./system/calendar"
# },
# {
#     "type": "path",
#     "url": "./system/development"
# },
# {
#     "type": "path",
#     "url": "./system/finance"
# },
# {
#     "type": "path",
#     "url": "./system/food"
# },
# {
#     "type": "path",
#     "url": "./system/maintenance"
# },
# {
#     "type": "path",
#     "url": "./system/news"
# },
# {
#     "type": "path",
#     "url": "./system/planning"
# },
# {
#     "type": "path",
#     "url": "./system/research"
# },
# {
#     "type": "path",
#     "url": "./system/greenhouse"
# },
# {
#     "type": "path",
#     "url": "./system/shopping"
# },
# {
#     "type": "path",
#     "url": "./system/weather"
# },
# {
#     "type": "path",
#     "url": "./system/wiretap"
# }