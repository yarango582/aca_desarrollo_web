#!/bin/sh
# Arranque de Apache. Se usa tanto en local (docker compose) como en Railway.
set -e

# Railway conserva a veces dos MPM de Apache en la imagen (event y prefork),
# lo que impide arrancar con "More than one MPM loaded". Dejamos solo prefork.
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*

# Railway inyecta la variable PORT; en local no existe y se usa el 80.
PORT="${PORT:-80}"
sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
