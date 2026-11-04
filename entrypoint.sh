#!/bin/sh
set -e

# Railway and dynamic cloud hosts provide $PORT
PORT="${PORT:-80}"

echo "Starting Apache on port ${PORT}..."
sed -i "s/Listen [0-9]*/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Execute standard apache foreground runner
exec apache2-foreground
