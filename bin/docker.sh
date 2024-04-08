#!/bin/bash

docker compose exec -u `id -u` app "$@"
