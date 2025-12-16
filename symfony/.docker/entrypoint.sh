#!/bin/sh
set -e

mkdir -p /var/www/html/var/cache
mkdir -p /var/www/html/var/log

chmod -R 775 /var/www/html/var

exec "$@"