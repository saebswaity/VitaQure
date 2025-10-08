# Use PHP 7.4 with Apache
FROM php:7.4-apache

# Set working directory inside container (dynamic, relative to container)
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    curl \
    && docker-php-ext-install zip pdo pdo_mysql mbstring

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files into container
COPY . /app

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Update Apache DocumentRoot to /app
RUN sed -i 's|/var/www/html|/app|g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|/var/www/html|/app|g' /etc/apache2/apache2.conf

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
