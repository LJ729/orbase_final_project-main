set -euo pipefall

PORT_TO_USE=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT_TO_USE}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT_TO_USE}/" /etc/apache2/sites-available/000-default.conf

php artisan storage:link || true
php artisan migrate --force || true
php artisan optimize

apache2-foreground