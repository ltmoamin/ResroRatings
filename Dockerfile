# Utiliser PHP 8.2 avec Apache
FROM php:8.2-apache

# Installer dépendances système et extensions PHP
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
    && docker-php-ext-install pdo pdo_mysql intl zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activer Apache rewrite et configurer le DocumentRoot
RUN a2enmod rewrite
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Variables d'environnement Symfony
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier tout le projet
COPY . .

# Installer les dépendances PHP sans exécuter les scripts Symfony
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Exposer le port Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
