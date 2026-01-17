FROM php:8.2-apache

# Installer dépendances système et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql intl zip gd

# Activer Apache rewrite
RUN a2enmod rewrite
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Variables d'environnement Symfony
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier composer.json et composer.lock et installer les dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copier le code source
COPY . .

# Exposer le port Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
