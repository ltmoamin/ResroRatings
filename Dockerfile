FROM php:8.1-apache
RUN apt-get update && apt-get install -y git unzip libicu-dev libonig-dev libzip-dev && docker-php-ext-install pdo pdo_mysql intl zip
RUN a2enmod rewrite
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www/html
ENV APP_ENV=prod
ENV APP_DEBUG=0
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
COPY . .
EXPOSE 80
CMD ["apache2-foreground"]
