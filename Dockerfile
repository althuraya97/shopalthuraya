FROM php:8.4-fpm

# تثبيت المتطلبات الضرورية لنظام PHP 8.4
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git curl libonig-dev libxml2-dev libicu-dev

# تثبيت إضافات PHP الضرورية
RUN docker-php-ext-install pdo_mysql mbstring gd intl

WORKDIR /var/www
COPY . .

# تثبيت Composer وتثبيت الملحقات مع تجاهل قيود المنصة لضمان التوافق
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# إعداد قاعدة بيانات SQLite والصلاحيات
RUN mkdir -p database && touch database/database.sqlite
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# المنفذ الذي يطلبه Render
EXPOSE 10000

# تشغيل الـ Migrations لإنشاء الجداول ثم تشغيل السيرفر
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
