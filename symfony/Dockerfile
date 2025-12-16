FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    git \
    make \
    curl \
    postgresql-dev \
    libzip-dev \
    libxml2-dev

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    zip \
    opcache \
    xml

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /usr/local/bin/docker/
COPY .docker/entrypoint.sh /usr/local/bin/docker/entrypoint.sh
RUN chmod +x /usr/local/bin/docker/entrypoint.sh

CMD ["/usr/local/bin/docker/entrypoint.sh"]

WORKDIR /var/www/html