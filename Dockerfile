FROM php:8.1-cli

# Set working directory
WORKDIR /srv/www

# Install required packages and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libzip-dev zip unzip git libonig-dev libicu-dev zlib1g-dev libxml2-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Copy application code
COPY . /srv/www

# Ensure writable log directory
RUN mkdir -p /srv/www/api && touch /srv/www/api/debug.log && chmod -R 777 /srv/www/api

# Default port (Render will provide PORT env at runtime)
ENV PORT=10000
EXPOSE ${PORT}

# Serve with PHP built-in server to respect $PORT at runtime
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t /srv/www"]
