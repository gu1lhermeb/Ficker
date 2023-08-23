FROM webdevops/php-apache:8.1-alpine

# Install Laravel framework system requirements (https://laravel.com/docs/8.x/deployment#optimizing-configuration-loading)
RUN apk update && apk upgrade
RUN apk add --update --no-cache oniguruma-dev libxml2-dev wget
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ctype
RUN docker-php-ext-install fileinfo
# RUN docker-php-ext-install json
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pdo
# RUN docker-php-ext-install tokenizer
# RUN docker-php-ext-install xmL
RUN docker-php-ext-install calendar

RUN set -ex \
    && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool \
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk add --no-cache --virtual .imagick-runtime-deps imagemagick \
    && apk del .phpize-deps

# Copy Composer binary from the Composer official Docker image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#Install nodejs via package manager
#RUN apk add --update --no-cache nodejs npm

# Clean up
RUN apk del php8-pear gcc musl-dev wget
RUN rm -rf /tmp/.zip /var/cache/apk/ /tmp/pear/

ENV APP_ENV=local
ENV PHP_DATE_TIMEZONE America/Maceio
ENV WEB_DOCUMENT_ROOT /app/public

#instalar dependências da aplicação via composer
RUN { echo "cd /app && composer install --no-interaction --optimize-autoloader"; \
    #gerar variável app key
    echo "php artisan key:generate"; \
    # Optimizing Route loading
    echo "php artisan route:cache"; \
    # Optimizing View loading
    echo "php artisan view:cache"; \
    #atualização da base de dados
    echo "php artisan migrate --force"; \
    } > /opt/docker/provision/entrypoint.d/start.sh

RUN chmod +x /opt/docker/provision/entrypoint.d/start.sh

WORKDIR /app

EXPOSE 80
