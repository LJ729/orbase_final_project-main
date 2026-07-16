#!/usr/bin/env bash
set -euo pipefail

PORT_TO_USE="${PORT:-80}"

# Render injects $PORT; Apache must listen on that port.
sed -i "s/^Listen .*/Listen ${PORT_TO_USE}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:.*>/<VirtualHost *:${PORT_TO_USE}>/" /etc/apache2/sites-available/000-default.conf

php artisan storage:link || true
php artisan package:discover --ansi || true
php artisan migrate --force || true
php artisan optimize || true

# Do not start Reverb here — run docker/start-reverb.sh as a separate
# Render web service (see render.yaml). Background workers are not reachable
# from the browser for WebSockets.

exec apache2-foreground