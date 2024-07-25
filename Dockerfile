ARG NODE_VERSION=22.2.0
ARG PHP_VERSION=8.3

### Web Service
FROM php:${PHP_VERSION}-alpine AS web-deps
ARG COMPOSER_VERSION="1.8.5"

WORKDIR /app

RUN apk update \
 && apk add git unzip \
 && curl https://getcomposer.org/download/$COMPOSER_VERSION/composer.phar --output /usr/bin/composer \
 && chmod u+x /usr/bin/composer

COPY web/composer.json composer.json
COPY web/composer.lock composer.lock

RUN composer install --no-scripts --no-autoloader

COPY web /app

RUN composer dump-autoload --optimize

FROM php:${PHP_VERSION}-fpm-alpine as web
WORKDIR /var/www
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli
COPY --from=web-deps /app /var/www/reon/web
RUN mkdir -p /var/www/reon/web/tmp \
    && chown www-data:www-data /var/www/reon/web/tmp


### Mail Service
FROM node:${NODE_VERSION}-alpine AS mail-deps
# Check https://github.com/nodejs/docker-node/tree/b4117f9333da4138b03a546ec926ef50a31506c3#nodealpine to understand why libc6-compat might be needed.
RUN apk add --no-cache libc6-compat jq
WORKDIR /app
COPY mail/package.json mail/package-lock.json* ./
RUN npm ci

FROM node:${NODE_VERSION}-alpine as mail
WORKDIR /app
COPY --from=mail-deps /app/node_modules ./node_modules
COPY mail /app
EXPOSE 25
EXPOSE 110

ENTRYPOINT ["/app/entrypoint.sh"]


### Cron jobs

FROM node:${NODE_VERSION}-alpine as battle-deps
WORKDIR /app
COPY app/pokemon-battle/package.json app/pokemon-battle/package-lock.json* ./
RUN npm ci

FROM node:${NODE_VERSION}-alpine as exchange-deps
WORKDIR /app
COPY app/pokemon-exchange/package.json app/pokemon-exchange/package-lock.json* ./
RUN npm ci

# Based on https://github.com/AnalogJ/docker-cron
FROM node:${NODE_VERSION}-alpine AS cron
COPY app/docker_entry.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

WORKDIR /app

COPY app/pokemon-battle pokemon-battle
COPY --from=battle-deps /app/node_modules ./pokemon-battle/node_modules

COPY app/pokemon-exchange pokemon-exchange
COPY --from=exchange-deps /app/node_modules ./pokemon-exchange/node_modules

COPY app/docker.crontab /etc/crontabs/root

# source: `docker run --rm -it alpine  crond -h`
# -f | Foreground
# -l N | Set log level. Most verbose 0, default 8
CMD ["crond", "-f", "-l", "2"]