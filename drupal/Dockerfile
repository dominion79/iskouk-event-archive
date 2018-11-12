FROM php:7.1-apache as build
RUN apt-get update && apt-get upgrade -y && apt-get install unzip libpng-dev libmemcached-dev zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev mediainfo git -y
RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir
RUN docker-php-ext-install gd
RUN docker-php-ext-install opcache && docker-php-ext-enable opcache

RUN echo "date.timezone = Europe/London" > /usr/local/etc/php/conf.d/timezone_set.ini
RUN groupmod -g 80 www-data # temporary workaround to facilitate the uid used in the shared filesystem in berwyn
RUN usermod -u 80 www-data
RUN rm -f /etc/apache2/sites-enabled/*
COPY ./apache/* /etc/apache2/sites-enabled/