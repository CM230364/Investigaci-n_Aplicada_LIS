# Imagen base con PHP y Apache
FROM php:8.3.14-apache

# Copiar todos los archivos del proyecto al servidor
COPY . /var/www/html/ProyectoLIS

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli

# Dar permisos al servidor web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Activar mod_rewrite
RUN a2enmod rewrite

# Habilitar configuración segura de directorios
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/permissions.conf \
    && a2enconf permissions

# Exponer el puerto por defecto
EXPOSE 80
