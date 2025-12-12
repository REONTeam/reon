ARG NODE_VERSION=25.2-trixie
ARG PHP_VERSION=8.3
ARG DOTNET_VERSION=9.0

### Legality checker
#See https://aka.ms/containerfastmode to understand how Visual Studio uses this Dockerfile to build your images for faster debugging.

FROM mcr.microsoft.com/dotnet/sdk:${DOTNET_VERSION} AS pokemon-legality
ARG DOTNET_VERSION
WORKDIR /src
COPY "app/pokemon-legality/LegalityCheckerConsole/LegalityCheckerConsole.csproj" /src/
RUN dotnet restore "LegalityCheckerConsole.csproj"
COPY "app/pokemon-legality/LegalityCheckerConsole/." .
RUN dotnet build "LegalityCheckerConsole.csproj" --no-restore -c Release --framework net${DOTNET_VERSION} -r linux-x64 --self-contained -o /app/pokemon-legality

### Web Service
FROM php:${PHP_VERSION} AS web-deps

WORKDIR /app

RUN apt-get -y update \
    && apt-get install -y --no-install-recommends git unzip \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY web/composer.json composer.json
COPY web/composer.lock composer.lock

RUN composer install --no-scripts --no-autoloader

COPY web /app

RUN composer dump-autoload --optimize

FROM php:${PHP_VERSION}-fpm AS web
WORKDIR /var/www
RUN apt-get -y update \
    && apt-get install -y --no-install-recommends libpng-dev libicu76 \
    && docker-php-ext-install mysqli gd \
    && docker-php-ext-enable mysqli gd \
    && apt-get remove -y libpng-dev \
    && rm -rf /var/lib/apt/lists/*
COPY --from=pokemon-legality /app/pokemon-legality /app/pokemon-legality
COPY --from=web-deps /app /var/www/reon/web
RUN mkdir -p /var/www/reon/web/tmp \
    && chown www-data:www-data /var/www/reon/web/tmp \
    && find /var/www/reon/web/htdocs -type f -exec chmod 644 {} \; \
    && find /var/www/reon/web/htdocs -type d -exec chmod 755 {} \;
ENV POKEMON_LEGALITY_BIN=/app/pokemon-legality/LegalityCheckerConsole

### Database Migration Service
FROM php:${PHP_VERSION} AS migrate
WORKDIR /var/www/reon

# Install MySQL client for database connectivity
RUN docker-php-ext-install mysqli pdo_mysql \
    && docker-php-ext-enable mysqli pdo_mysql

# Copy composer dependencies and phinx
COPY --from=web-deps /app /var/www/reon/web

# Copy migration files and config
COPY phinx.php /var/www/reon/phinx.php
COPY db/ /var/www/reon/db/

CMD ["/var/www/reon/web/vendor/bin/phinx", "migrate"]

### Mail Service
FROM node:${NODE_VERSION} AS mail-deps
# Check https://github.com/nodejs/docker-node/tree/b4117f9333da4138b03a546ec926ef50a31506c3#nodealpine to understand why libc6-compat might be needed.
RUN apt-get -y update \
    && apt-get install -y --no-install-recommends libc6-compat jq
WORKDIR /app
COPY mail/package.json mail/package-lock.json* ./
RUN npm ci

FROM node:${NODE_VERSION} AS mail
WORKDIR /app
COPY --from=mail-deps /app/node_modules ./node_modules
COPY mail /app
EXPOSE 25
EXPOSE 110

ENTRYPOINT ["/app/entrypoint.sh"]


### Cron jobs

FROM node:${NODE_VERSION} AS battle-deps
WORKDIR /app
COPY app/pokemon-battle/package.json app/pokemon-battle/package-lock.json* ./
RUN npm ci

FROM node:${NODE_VERSION} AS exchange-deps
WORKDIR /app
COPY app/pokemon-exchange/package.json app/pokemon-exchange/package-lock.json* ./
RUN npm ci

FROM node:${NODE_VERSION} AS bottle-deps
WORKDIR /app
COPY app/mail-bottle/package.json app/mail-bottle/package-lock.json* ./
RUN npm ci

# Based on https://github.com/AnalogJ/docker-cron
FROM node:${NODE_VERSION} AS cron
RUN 
RUN apt-get -y update \
    && apt-get install -y --no-install-recommends cron libicu76 \
    && rm -rf /var/lib/apt/lists/*
COPY app/docker_entry.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

WORKDIR /app

COPY app/pokemon-battle pokemon-battle
COPY --from=battle-deps /app/node_modules ./pokemon-battle/node_modules

COPY app/pokemon-exchange pokemon-exchange
COPY --from=exchange-deps /app/node_modules ./pokemon-exchange/node_modules

COPY app/mail-bottle mail-bottle
COPY --from=bottle-deps /app/node_modules ./mail-bottle/node_modules

COPY --from=pokemon-legality /app/pokemon-legality /app/pokemon-legality
ENV POKEMON_LEGALITY_BIN=/app/pokemon-legality/LegalityCheckerConsole

COPY app/bxt_config_loader.js /app/

COPY app/docker.crontab /etc/crontabs/root

# source: `docker run --rm -it alpine  crond -h`
# -f | Foreground
# -l N | Set log level. Most verbose 0, default 8
#CMD ["crond", "-f", "-l", "2"]
CMD ["cron", "-f"]

### DNS server

FROM alpine:3.20 AS dns
RUN apk --no-cache add dnsmasq
COPY docker-dns-entry.sh /entrypoint.sh
EXPOSE 53/udp
ENTRYPOINT ["/entrypoint.sh"]
