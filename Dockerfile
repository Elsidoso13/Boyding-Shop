FROM php:8.1-apache

# Habilitar extensiones PHP necesarias (para bases de datos)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Crear la estructura de carpetas que tu código espera
RUN mkdir -p /var/www/html/Pagina

# Copiar todos los archivos a la carpeta Pagina
COPY . /var/www/html/Pagina/

# Configurar Apache para que el DocumentRoot sea /var/www/html/Pagina
ENV APACHE_DOCUMENT_ROOT /var/www/html/Pagina
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]