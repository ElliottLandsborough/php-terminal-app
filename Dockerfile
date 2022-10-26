FROM php

RUN apt-get update && apt-get install -y curl git libzip-dev zip \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer