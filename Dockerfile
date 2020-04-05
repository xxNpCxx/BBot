FROM php:7.4.3-fpm-alpine AS php

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

RUN apk add bash

USER $DOCKER_USER