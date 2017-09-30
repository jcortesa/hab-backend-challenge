FROM alpine:3.5

LABEL maintainer "jcortesanguita@gmail.com"

ARG ENV=prod

RUN apk add --no-cache --update \
    php7 \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-fpm \
    php7-gd \
    php7-iconv \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-mcrypt \
    php7-mysqlnd \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-phar \
    php7-posix \
    php7-session \
    php7-xml \
    php7-zlib \
    php7-apcu \
    curl \
    unzip \
    wget \
    git

COPY docker-files/symfony.ini /etc/php7/conf.d/50_setting.ini
COPY docker-files/symfony.pool.conf /etc/php7/php-fpm.conf

RUN ln -s /usr/bin/php7 /usr/local/bin/php
# install Composer
RUN curl -s https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -f installer

RUN wget -q https://phar.phpunit.de/phpunit.phar \
    && chmod +x phpunit.phar \
    && mv phpunit.phar /usr/local/bin/phpunit \
    && phpunit --version

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

COPY ./symfony /src

RUN rm -rf /var/www/symfony\
    && mkdir -p /var/www\
    && mv /src /var/www/symfony\
    && find /var/www/symfony/ -type d -exec chmod 755 {} \;\
    && find /var/www/symfony/ -type f -exec chmod 644 {} \;\
    && cd /var/www/symfony\
    && composer install -o
    # && php bin/console cache:clear --env=prod\
    # && rm -r app/cache/*\
    # && php bin/console cache:warmup --env=prod\
    # && chmod -R 777 app/cache\
    # && chmod -R 777 app/logs

ADD docker-files/nginx.conf /etc/nginx/conf.d/default.conf
VOLUME /etc/nginx/conf.d/


WORKDIR /var/www/symfony
VOLUME /var/www/symfony

EXPOSE 9000

CMD ["sh", "php-fpm7 -F"]
