#!/bin/bash

touch database/db.sqlite
docker compose up -d
bin/docker.sh composer install -n
bin/docker.sh ./artisan migrate:fresh --force
