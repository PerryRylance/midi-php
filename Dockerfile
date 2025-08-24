ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-cli

EXPOSE 9003

ENV COMPOSER_ALLOW_SUPERUSER=1

ARG DEV_MODE=0
ENV DEV_MODE=${DEV_MODE}

RUN apt-get update && apt-get install -y git

RUN git config --global --add safe.directory /app

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip xdebug \
    && echo "xdebug.mode=debug\n\
xdebug.client_host=host.docker.internal\n\
xdebug.start_with_request=yes\n\
xdebug.idekey=docker\n\
xdebug.discover_client_host = 1\n\
" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

RUN composer install --dev

CMD ["sh", "-c", "if [ \"$DEV_MODE\" = 1 ]; then tail -f /dev/null; else composer run test; fi"]
