FROM php:8.2-apache

ENV SERVER_NAME=brie.test

# RUN echo "ServerName ${SERVER_NAME}" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite headers

RUN apt-get update \
    && apt-get install -y autoconf gcc g++ make zip unzip \
    && apt-get install -y libpng-dev libjpeg-dev \
    && apt-get install -y libicu-dev libxml2-dev libzip-dev openssl libssl-dev

RUN docker-php-ext-install pdo pdo_mysql gd bcmath intl soap zip ftp

RUN apt-get install -y locales locales-all
ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8
