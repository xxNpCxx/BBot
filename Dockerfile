FROM php:7.2-fpm-alpine AS php

RUN apk add bash && \
    apk add git

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install sockets

RUN apk add autoconf gcc libzmq zeromq-dev zeromq coreutils build-base sudo && \
    pecl install zmq-beta

RUN docker-php-ext-enable zmq

RUN git clone https://github.com/mongodb/mongo-php-driver.git && \
    cd mongo-php-driver && \
    git submodule update --init && \
    phpize && \
    ./configure --enable-ssl && \
    make all && \
    make install

RUN apk add openssl-dev

RUN pecl install mongodb && \
echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini