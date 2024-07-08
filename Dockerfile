FROM php:8.2-apache

RUN pecl install redis && docker-php-ext-enable redis

COPY ./php.ini /usr/local/etc/php/php.ini