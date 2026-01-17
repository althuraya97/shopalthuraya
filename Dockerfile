FROM php:8.2-fpm

# تثبيت المتطلبات الضرورية
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git curl libonig-dev libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring gd

WORKDIR /var/www
COPY . .

# تثبيت Composer وتثبيت الملحقات
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# إعداد صلاحيات المجلدات وقاعدة البيانات
RUN mkdir -p database && touch database/database.sqlite
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

# المنفذ الذي يطلبه Render
EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
