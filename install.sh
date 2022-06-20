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
    docker run --rm -v "$(pwd)":/opt -w /opt austinkregel/base:latest bash -c "composer install"
else
    composer install
fi

vendor/bin/sail up -d

vendor/bin/sail artisan key:generate

vendor/bin/sail artisan install
