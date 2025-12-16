#!/usr/bin/env bash

docker compose up -d
docker compose exec symfony composer install --no-interaction --no-scripts --no-progress