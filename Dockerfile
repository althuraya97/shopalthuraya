FROM php:8.4-fpm

# تثبيت المتطلبات الضرورية لنظام PHP 8.4
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git curl libonig-dev libxml2-dev libicu-dev

# تثبيت إضافات PHP
RUN docker-php-ext-install pdo_mysql mbstring gd intl

WORKDIR /var/www
COPY . .

# إعداد Composer وتثبيت الملحقات مع تجاهل قيود المنصة مؤقتاً لضمان النجاح
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# إعداد قاعدة البيانات والصلاحيات
RUN mkdir -p database && touch database/database.sqlite
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
