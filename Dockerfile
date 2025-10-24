# Image de base PHP avec Apache
FROM php:8.3-apache

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Créer le fichier .env à partir de .env.example
RUN if [ -f .env.example ]; then cp .env.example .env; else \
    echo "APP_NAME=Laravel\nAPP_ENV=production\nAPP_KEY=\nAPP_DEBUG=false\nAPP_URL=http://localhost" > .env; \
    fi

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Générer la clé d'application
RUN php artisan key:generate

# Installer les dépendances NPM et compiler les assets
RUN npm ci && npm run build

# Configurer Apache
RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Port exposé
EXPOSE 80

# Créer un script d'entrypoint
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Attendre que MySQL soit prêt\n\
echo "Waiting for MySQL..."\n\
until php artisan migrate:status 2>/dev/null; do\n\
  echo "MySQL not ready, waiting..."\n\
  sleep 2\n\
done\n\
\n\
echo "Running migrations..."\n\
php artisan migrate --force || true\n\
\n\
echo "Optimizing Laravel..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "Starting Apache..."\n\
exec apache2-foreground' > /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Commande de démarrage
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

