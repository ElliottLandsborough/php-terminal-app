FROM php:8.1

RUN apt-get update && apt-get install -y --no-install-recommends curl git libzip-dev zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer