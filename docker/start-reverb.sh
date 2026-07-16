#!/usr/bin/env bash
set -euo pipefail

# Render injects $PORT for public web services. Reverb must bind to it.
PORT_TO_USE="${PORT:-8080}"

php artisan package:discover --ansi || true
php artisan config:cache || true

echo "Starting Reverb on 0.0.0.0:${PORT_TO_USE}"

exec php artisan reverb:start \
    --host=0.0.0.0 \
    --port="${PORT_TO_USE}"