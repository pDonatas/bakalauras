FROM php:8.2-fpm

ARG user
ARG uid

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

# Install the PHP zip, git
RUN apt-get update && apt-get install -y libzip-dev zip unzip git
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

EXPOSE 5000

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www/html

RUN chown -R $user:$user /var/www/html

RUN apt install libsodium-dev -y
RUN docker-php-ext-install bcmath pdo_mysql sodium zip

# Install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

CMD ["php-fpm"]
