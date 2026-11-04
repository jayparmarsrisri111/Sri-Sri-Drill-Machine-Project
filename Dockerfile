FROM php:8.2-apache

# Install SQLite and PDO extensions
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Suppress Apache ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configure AllowOverride All for .htaccess support
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set working directory and copy all project files
WORKDIR /var/www/html
COPY . /var/www/html/

# Ensure write permissions for SQLite database and uploads directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/database \
    && chmod -R 777 /var/www/html/assets/images/machinery

# Expose default port
EXPOSE 80

# Inline startup: Dynamically bind Apache to Railway's $PORT and start foreground server
CMD sh -c "sed -i \"s/Listen [0-9]*/Listen \${PORT:-80}/g\" /etc/apache2/ports.conf && sed -i \"s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:\${PORT:-80}>/g\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"
