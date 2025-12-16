#!/usr/bin/env bash

docker compose up -d
docker compose exec symfony composer install --no-interaction --no-scripts --no-progress
docker compose exec symfony php bin/console doctrine:migrations:migrate --no-interaction