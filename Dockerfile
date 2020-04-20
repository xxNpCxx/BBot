FROM php:7.2-fpm-alpine AS php

RUN apk add bash && \
    apk add git

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install sockets

RUN apk --update add --virtual build-dependencies build-base openssl-dev autoconf \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb \
  && apk del build-dependencies build-base openssl-dev autoconf \
  && rm -rf /var/cache/apk/*

RUN apk add autoconf gcc libzmq zeromq-dev zeromq coreutils build-base sudo && \
    pecl install zmq-beta

RUN docker-php-ext-enable zmq
