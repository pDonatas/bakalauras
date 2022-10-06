FROM php:8.1-fpm

USER root

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install git -y

# Install the PHP zip
RUN apt-get update && apt-get install -y libzip-dev zip unzip
RUN docker-php-ext-install zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# Install node
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Install yarn
RUN npm install -g yarn

# Install Nginx
RUN apt-get update && apt-get install -y nginx

# Copy nginx config
COPY docker/8.1/nginx.conf /etc/nginx/sites-available/default

COPY docker/8.1/start-container /etc/entrypoint.sh

RUN chmod +x /etc/entrypoint.sh

WORKDIR /var/www/html

EXPOSE 80 443 5000

ENTRYPOINT ["/etc/entrypoint.sh"]
