#!/bin/bash

touch database/db.sqlite
docker compose up -d
docker compose exec app composer install
bin/docker.sh ./artisan migrate:fresh
