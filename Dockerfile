FROM php:8.2-fpm

RUN apt-get update && apt-get install -y libzip-dev libpq-dev libicu-dev
RUN docker-php-ext-configure zip
RUN docker-php-ext-install -j$(nproc) zip pdo_pgsql intl
RUN pecl install redis && docker-php-ext-enable redis


RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get install -y symfony-cli

WORKDIR /app/

COPY composer.json /app/

RUN composer install --no-interaction --no-scripts

COPY . /app/
# Expose the port your Symfony app will run on (replace 8000 with your actual port)
EXPOSE 0000

# Start the server using the Symfony Runtime
CMD ["symfony", "server:start", "--no-tls", "--port=8000", "--dir=public"]