# Étape 1 : Utilisez une image de base avec PHP et Apache
FROM php:8.1-apache

# Étape 2 : Activez le module Apache pour la réécriture d'URL
RUN a2enmod rewrite

# Étape 3 : Installez les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl

# Étape 4 : Copiez les fichiers de l'application
COPY . /var/www/html/

# Étape 5 : Définissez le répertoire de travail
WORKDIR /var/www/html

# Étape 6 : Configurez les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Étape 7 : Configurez Apache
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Étape 8 : Installez et activez les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-enable zip

# Étape 9 : Exposez le port 80
EXPOSE 80

# Étape 10 : Démarrez Apache
CMD ["apache2-foreground"]
