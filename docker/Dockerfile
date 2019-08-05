FROM php:7.3-apache 

COPY php.ini /usr/local/etc/php/
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY ssmtp.conf /etc/ssmtp/

# generate SSL cert for testing purposes
#RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/CN=localhost"

# enable apache modules
RUN a2enmod expires headers rewrite

# install extensions
RUN apt-get update -q && apt-get install -qy \
       libfreetype6-dev \
       libjpeg62-turbo-dev \
       libmcrypt-dev \
       libpng-dev \
       libzip-dev \
       unzip \
       dos2unix \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip mysqli

## start apache
CMD ["apache2-foreground"]