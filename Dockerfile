FROM php:8.1-apache

# Habilitar extensiones PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar archivos al DocumentRoot
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]