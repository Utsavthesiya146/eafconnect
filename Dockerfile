FROM php:8.2-apache

# 1. Install system dependencies and PHP extensions required by CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql intl zip gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# 2. Set working directory
WORKDIR /var/www/html

# 3. Copy application code into container
COPY . /var/www/html/

# 4. Set proper permissions for CodeIgniter writable folder and uploads
RUN chown -R www-data:www-data /var/www/html/writable /var/www/html/public /var/www/html/uploads \
    && chmod -R 775 /var/www/html/writable /var/www/html/uploads \
    && ln -s /var/www/html/assets2 /var/www/html/preview/assets2 \
    && ln -s /var/www/html/images /var/www/html/preview/images \
    && ln -s /var/www/html/uploads /var/www/html/preview/uploads

# 5. Configure Apache to support .htaccess rewrites, reverse proxies, and Render dynamic PORT
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo "UseCanonicalName Off" >> /etc/apache2/apache2.conf \
    && echo "UseCanonicalPhysicalPort Off" >> /etc/apache2/apache2.conf

ENV PORT=80
EXPOSE 80

CMD ["apache2-foreground"]

