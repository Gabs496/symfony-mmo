FROM php:8.3-fpm
RUN apt update && apt install -y \
    librabbitmq-dev \
    libssh-dev \
    libzip-dev \
    && docker-php-ext-install bcmath mysqli pdo_mysql \
    && pecl install apcu amqp xdebug \
    && docker-php-ext-enable apcu bcmath mysqli amqp xdebug \