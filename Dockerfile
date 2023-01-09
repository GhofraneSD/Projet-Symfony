FROM richarvey/nginx-php-fpm:1.10.0

RUN [[ -z "${http_proxy}" ]] && echo 'no proxy for pecl' || pear config-set http_proxy ${http_proxy} \
  && pecl channel-update pecl.php.net

ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev openldap-dev && \
    docker-php-ext-install ldap && \
    docker-php-ext-enable ldap && \
    apk del .build-deps && \
    apk add --update make && \
    apk add --no-cache git

RUN set -xe \
    && apk add --no-cache --virtual .build-deps g++ make autoconf yaml-dev \
    && pecl install apcu \
    && pecl install apcu_bc \
    && docker-php-ext-enable --ini-name 0-apc.ini apcu apc \
    && docker-php-ext-install bcmath sockets \
    && apk add --no-cache --update libmemcached-libs zlib \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --no-cache --update --virtual .memcached-deps $MEMCACHED_DEPS \
    && pecl install memcached \
    && docker-php-ext-enable memcached \
    && docker-php-ext-enable xdebug \
    && rm -rf /usr/share/php7 \
    && rm -rf /tmp/* \
    && apk del .memcached-deps .phpize-deps .build-deps \
    && apk add --update xvfb \
    && apk add libxrender libc6-compat libxext fontconfig
RUN apk add optipng

RUN apk add --no-cache --no-progress --virtual BUILD_DEPS_PHP_AMQP rabbitmq-c-dev \
    && apk add --no-cache --no-progress rabbitmq-c \
    && apk add --no-cache git autoconf automake gawk build-base \
    && pecl install amqp \
    && docker-php-ext-enable amqp\
    && apk add --update npm

COPY ./ /var/www/html/

WORKDIR /var/www/html

RUN php -d memory_limit=-1 /usr/bin/composer global require hirak/prestissimo \
    && php -d memory_limit=-1 /usr/bin/composer install --no-scripts -n \
    && php -d memory_limit=-1 /usr/bin/composer clear-cache \
    && npm install \
    && npm run build

# launch docker source functions (see https://gitlab.com/suezenv/nginx-php-fpm/blob/master/scripts/start.sh)
ENV SKIP_CHOWN=true
ENV RUN_SCRIPTS=1
ENV SKIP_COMPOSER=true


