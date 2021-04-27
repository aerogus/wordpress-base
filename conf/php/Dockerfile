FROM php:7.4-fpm

LABEL maintainer="Guillaume Seznec <guillaume@seznec.fr>"

RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install gettext \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install gd

COPY php.ini /usr/local/etc/php/

RUN mkdir /var/log/php \
  && chmod 777 /var/log/php

