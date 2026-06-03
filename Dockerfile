FROM php:8.2-apache

# Copy project into apache folder
COPY . /var/www/html/

# Enable rewrite (optional but helpful)
RUN a2enmod rewrite