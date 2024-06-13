# Web Service
FROM php:8.3.2-fpm as web
RUN apt-get update \
    && apt-get install -y libzip-dev sendmail \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Sendmail config
RUN echo "sendmail_path=/usr/sbin/sendmail -t -i" >> /usr/local/etc/php/conf.d/sendmail.ini
# Start sendmail service when container starts
RUN sed -i '/#!\/bin\/sh/aservice sendmail restart' /usr/local/bin/docker-php-entrypoint
# Add docker hostname to /etc/hosts
RUN sed -i '/#!\/bin\/sh/aecho "$(hostname -i)\t$(hostname) $(hostname).localhost" >> /etc/hosts' /usr/local/bin/docker-php-entrypoint

COPY --from=composer/composer:latest-bin /composer /usr/local/bin/composer
COPY web /var/www/reon/web
WORKDIR /var/www/reon/web
RUN composer install && chown -R www-data:www-data /var/www/reon;


# Mail Service
FROM node:22.2.0 as mail
COPY mail /home/node/app
RUN cd /home/node/app && npm install
WORKDIR /home/node/app
CMD ["npm", "start"]