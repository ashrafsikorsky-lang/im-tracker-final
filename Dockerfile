# 1. Use the official PHP image
FROM php:8.2-cli

# 2. Install necessary server tools and MySQL extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Move into the app directory
WORKDIR /app

# 5. Copy your project files into the server
COPY . .

# 6. Install your project dependencies
RUN composer install --optimize-autoloader
RUN npm install && npm run build

# 7. Start the Laravel server (Render provides the $PORT variable automatically)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}