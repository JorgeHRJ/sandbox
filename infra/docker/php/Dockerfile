FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN apt-get update && apt-get install -y vim curl git unzip wget software-properties-common

# php 8 setup
RUN add-apt-repository -y ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y php8.0-common php8.0-cli php8.0-mysql php8.0-curl php8.0-mbstring php8.0-intl php8.0-xml

ADD etc/php/8.0/cli/conf.d/ /etc/php/8.0/cli/conf.d/
RUN mkdir /run/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
RUN curl https://get.sensiolabs.org/security-checker.phar -O
RUN mv security-checker.phar /usr/bin/security-checker
RUN chmod +x /usr/bin/security-checker

# php-fpm 8 setup
RUN apt-get update && apt-get install -y php8.0-fpm

ADD etc/php/8.0/fpm/conf.d/ /etc/php/8.0/fpm/conf.d/
ADD etc/php/8.0/fpm/pool.d/www.conf /etc/php/8.0/fpm/pool.d/

CMD ["php-fpm8.0", "-F"]
