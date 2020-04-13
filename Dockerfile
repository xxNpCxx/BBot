FROM php:7.2-fpm-alpine AS php

ARG DOCKER_USER='docker'
ARG DOCKER_GROUP='docker'
ARG DOCKER_HOME=/home/${DOCKER_USER}

RUN addgroup --gid 1000 $DOCKER_GROUP && \
    adduser --uid 1000 \
            --ingroup $DOCKER_GROUP \
            --home $DOCKER_HOME \
            --shell /bin/sh \
            --disabled-password \
            --gecos "" $DOCKER_USER

RUN apk add bash && \
    apk add git

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install sockets

RUN apk add autoconf gcc libzmq zeromq-dev zeromq coreutils build-base && \
    pecl install zmq-beta

RUN docker-php-ext-enable zmq

USER $DOCKER_USER