FROM php:8.4-fpm

# تثبيت المتطلبات الأساسية للنظام
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git curl libonig-dev libxml2-dev libicu-dev

# تثبيت ملحقات PHP
RUN docker-php-ext-install pdo_mysql mbstring gd intl

WORKDIR /var/www
COPY . .

# تثبيت Composer وتجاوز قيود الإصدارات للتوافق
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# إعداد الصلاحيات وقاعدة البيانات SQLite
RUN mkdir -p database storage bootstrap/cache
RUN touch database/database.sqlite
RUN chmod -R 777 storage bootstrap/cache database
RUN chown -R www-data:www-data /var/www

EXPOSE 10000

# تهجير قاعدة البيانات وتشغيل السيرفر
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
