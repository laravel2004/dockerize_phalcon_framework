FROM php:8.2-fpm

ENV COMPOSER_MEMORY_LIMIT='-1'

ENV PS1="\u@\h:\w\\$ "
ENV TZ="Asia/Jakarta"

RUN apt-get update && \
    apt-get install -y --force-yes --no-install-recommends \
        libmemcached-dev \
        libmcrypt-dev \
        libreadline-dev \
        libgmp-dev \
        libzip-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        openssh-server \
        libmagickwand-dev \
        git \
        cron \
        nano \
        libxml2-dev

# Install soap extension
RUN docker-php-ext-install soap

# Install exif extension for image manipulation
RUN docker-php-ext-install exif

# Install the PHP pcntl extension
RUN docker-php-ext-install pcntl

# Install the PHP intl extension
RUN docker-php-ext-install intl

# Install the PHP gmp extension
RUN docker-php-ext-install gmp

# Install the PHP zip extension
RUN docker-php-ext-install zip

# Install the PHP pdo_mysql extension
RUN docker-php-ext-install pdo_mysql

# Install the PHP pdo_pgsql extension
RUN docker-php-ext-install pdo_pgsql

# Install the PHP bcmath extension
RUN docker-php-ext-install bcmath

#####################################
# PHPRedis:
#####################################
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install mongodb && docker-php-ext-enable mongodb

#####################################
# Imagick:
#####################################
RUN pecl install imagick && \
    docker-php-ext-enable imagick

#####################################
# GD:
#####################################
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

#####################################
# xDebug:
#####################################
# Install the xdebug extension
RUN pecl install xdebug

#####################################
# PHP Memcached:
#####################################
# Install the php memcached extension
RUN pecl install memcached && docker-php-ext-enable memcached

#####################################
# Composer:
#####################################
# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

#####################################
# Extension Phalcon:
#####################################
# Install dependencies untuk build Phalcon
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    gcc \
    make \
    autoconf \
    libc-dev \
    pkg-config \
    re2c \
    libpcre3-dev

# Download dan compile Phalcon dari sumber
RUN curl -sSL "https://github.com/phalcon/cphalcon/archive/refs/tags/v5.3.0.tar.gz" -o phalcon.tar.gz && \
    tar -xzf phalcon.tar.gz && \
    cd cphalcon-5.3.0/build && \
    ./install && \
    cd ../../ && \
    rm -rf cphalcon-5.3.0 phalcon.tar.gz

# Enable Phalcon extension
RUN echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/phalcon.ini


ADD ./local.ini /usr/local/etc/php/conf.d


RUN rm -r /var/lib/apt/lists/*

WORKDIR /var/www/html


EXPOSE 9000
CMD ["php-fpm"]