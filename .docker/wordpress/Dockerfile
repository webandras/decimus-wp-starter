FROM wordpress:php8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y sudo \
    less default-mysql-client nano iputils-ping libxml2-dev zip unzip

# Cleanup
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install WP-CLI
COPY wp-su.sh /usr/local/bin/wp
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
  && chmod +x wp-cli.phar /usr/local/bin/wp \
  && mv wp-cli.phar /usr/local/bin/wp-cli.phar

# Install Xdebug
ENV XDEBUG_PORT 9001
ENV XDEBUG_IDEKEY docker
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY xdebug.ini "$PHP_INI_DIR/conf.d/"

RUN docker-php-ext-install xml fileinfo 


ENTRYPOINT []
CMD ["php-fpm"]
