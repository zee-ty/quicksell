# Use official PHP with Apache
FROM php:8.1-apache

# Install common PHP extensions (adjust if your app needs others)
RUN apt-get update \
	&& apt-get install -y --no-install-recommends libzip-dev zip unzip libpng-dev \
	&& docker-php-ext-install pdo pdo_mysql mysqli \
	&& rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy application code into the web root
COPY . /var/www/html/
WORKDIR /var/www/html

# Ensure entrypoint script is installed and executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose HTTP
EXPOSE 80

# Start via custom entrypoint which runs Apache in foreground
CMD ["/usr/local/bin/docker-entrypoint.sh"]