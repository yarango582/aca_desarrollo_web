FROM php:8.2-apache

# Extensiones necesarias para conectar a MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Apache: habilitar URLs limpias y apuntar el DocumentRoot a /public
RUN a2enmod rewrite \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# En desarrollo el código se monta como volumen (ver docker-compose.yml).
# Para una imagen autocontenida se copia el proyecto:
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

# Script de arranque: corrige el MPM duplicado y escucha en $PORT (Railway)
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh \
    && rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*

EXPOSE 80
CMD ["start.sh"]
