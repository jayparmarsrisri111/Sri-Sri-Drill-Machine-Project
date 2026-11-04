FROM php:8.2-apache

# Install SQLite and PDO extensions
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure AllowOverride All for .htaccess support
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set working directory and copy codebase
WORKDIR /var/www/html
COPY . /var/www/html/

# Ensure write permissions for SQLite database and uploads
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/database \
    && chmod -R 777 /var/www/html/assets/images/machinery

# Port configuration script for dynamic host ports (Railway/Render)
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENV PORT=80
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
