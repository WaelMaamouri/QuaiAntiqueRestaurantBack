# Utilise l'image PHP officielle avec extensions pour Symfony
FROM php:8.2-cli

# Installe les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install pdo_mysql

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copie le projet dans le conteneur
WORKDIR /app
COPY . /app

# Installe les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Commande pour lancer Symfony
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
