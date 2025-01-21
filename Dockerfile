# Use an official Apache image with PHP
FROM php:8.1-apache

# Enable rewrites
RUN a2enmod rewrite

# Install MySQL support:
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your PHP application into the container (adjust the path)
# COPY . /var/www/html/

# Expose port 80 for web traffic
EXPOSE 80

# Start Apache in the foreground (default behavior for this image)
CMD ["apache2-foreground"]