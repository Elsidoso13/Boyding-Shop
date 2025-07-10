FROM php:8.1-apache

# Instalar dependencias del sistema para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Habilitar extensiones PHP para PostgreSQL
RUN docker-php-ext-install pgsql pdo pdo_pgsql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Crear la estructura de carpetas que tu código espera
RUN mkdir -p /var/www/html/Pagina

# Copiar todos los archivos a la carpeta Pagina
COPY . /var/www/html/Pagina/

# Configurar Apache para servir desde /var/www/html/Pagina
ENV APACHE_DOCUMENT_ROOT /var/www/html/Pagina
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurar permisos correctos
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

# Configurar Apache para permitir acceso a archivos
RUN echo '<Directory /var/www/html/Pagina>' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

# Crear un .htaccess básico si no existe
RUN echo 'DirectoryIndex index.php index.html' > /var/www/html/Pagina/.htaccess

EXPOSE 80
CMD ["apache2-foreground"]