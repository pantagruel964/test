FROM php:8.0-fpm

ARG user
ARG uid

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git curl unzip netcat supervisor cron libreadline-dev \
    libicu-dev libpng-dev libonig-dev libxml2 libxml2-dev libzip-dev libssl-dev libzip-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*; \

    docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_mysql mysqli gd mbstring exif iconv intl pcntl bcmath && \
    docker-php-ext-enable pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

USER $user
