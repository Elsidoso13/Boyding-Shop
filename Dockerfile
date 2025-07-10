FROM php:8.1-apache

# Instalar dependencias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev && rm -rf /var/lib/apt/lists/*

# Habilitar extensiones PHP para PostgreSQL
RUN docker-php-ext-install pgsql pdo pdo_pgsql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar archivos a la raíz de Apache
COPY . /var/www/html/

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/

# Configurar Apache
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]