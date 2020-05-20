# Deps
FROM composer AS composer

COPY . /app

RUN composer install

# Final
FROM php:7.3-apache-stretch

COPY --from=composer /app /var/www/html
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start

RUN chown -R www-data:www-data /var/www/html \
    && chmod u+x /usr/local/bin/start \
    && a2enmod rewrite \
    && docker-php-ext-install pdo_mysql

CMD ["/usr/local/bin/start"]
