FROM php:8.2-apache

# Enable rewrite (safe for most PHP apps)
RUN a2enmod rewrite

# Disable conflicting MPM modules (THIS fixes your error)
RUN a2dismod mpm_event || true
RUN a2enmod mpm_prefork

# Copy project
COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 80