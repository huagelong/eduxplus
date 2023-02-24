FROM alpine:3.15
WORKDIR /var/www/symfony
RUN apk add --update --no-cache \
    coreutils \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-redis \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xdebug \
    php7-zip \
    php7-xmlwriter \
    make \
    curl \
    git \
    busybox-extras

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

COPY ./docs/docker/php-fpm/symfony.ini /etc/php7/conf.d/
COPY ./docs/docker/php-fpm/symfony.ini /etc/php7/cli/conf.d/
COPY ./docs/docker/php-fpm/xdebug.ini  /etc/php7/conf.d/

COPY ./docs/docker/php-fpm/symfony.pool.conf /etc/php7/php-fpm.d/

COPY . ./
RUN composer config -g repo.packagist composer https://packagist.phpcomposer.com
RUN composer install

RUN echo "* * * * * cd /var/www/symfony && php ./bin/console schedule:run >> /dev/null 2>&1" >> /etc/crontabs/root
CMD ["php-fpm7", "-F"]

RUN apk add --update --no-cache nginx

COPY nginx.conf /etc/nginx/
COPY symfony.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
