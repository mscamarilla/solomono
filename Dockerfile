FROM php:7.4-fpm-alpine

RUN apk update \
  && apk add --no-cache libzip-dev php7-zip php7-mysqli libcurl curl curl-dev \
  && docker-php-ext-install zip mysqli curl \
  && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
  && curl --silent --show-error https://getcomposer.org/installer | php \
  && mv composer.phar /usr/bin/composer \
  && rm -rf /tmp/*

  WORKDIR /application