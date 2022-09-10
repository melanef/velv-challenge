FROM php:8.1-fpm

# Arguments defined in docker-compose.yml
ARG WWWUSER
ARG WWWGROUP
ARG WWWUID

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd soap zip

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $WWWUID -d /home/$WWWUSER $WWWUSER &&\
    mkdir -p /home/$WWWUSER/.composer && \
    chown -R $WWWUSER:$WWWGROUP /home/$WWWUSER

# Set current user
USER $WWWUSER:$WWWGROUP

WORKDIR /var/www/html/
