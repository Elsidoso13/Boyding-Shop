FROM php:8.1-apache

# Habilitar extensiones PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Crear la estructura de carpetas que tu código espera
RUN mkdir -p /var/www/html/Pagina

# Copiar todos los archivos a la carpeta Pagina
COPY . /var/www/html/Pagina/

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]