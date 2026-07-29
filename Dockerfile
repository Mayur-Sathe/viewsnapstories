FROM php:8.3-apache
COPY . /var/www/html/
RUN a2enmod rewrite && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
