#!/bin/bash

touch database/db.sqlite
docker compose up -d
docker compose exec app composer install -n
bin/docker.sh ./artisan migrate:fresh
