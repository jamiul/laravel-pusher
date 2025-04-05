FROM php:8.4-fpm

ARG USER
ARG USER_ID
ARG GROUP_ID

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    vim \
    libicu-dev \
    # Add these for better Node.js support
    build-essential \
    python3 \
    # Add this for watching file changes (helpful for Vite)
    inotify-tools

# Install Node.js 20 (more stable for Vite than 22)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash \
    && apt-get install nodejs -y

# Install npm globally
RUN npm install -g npm@latest

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql intl opcache

# Set up user
RUN groupadd --force -g $GROUP_ID $USER
RUN useradd -ms /bin/bash --no-user-group -g $GROUP_ID -u 1337 $USER
RUN usermod -u $USER_ID $USER

# Create directory for npm cache and set permissions
RUN mkdir -p /home/$USER/.npm && chown -R $USER:$GROUP_ID /home/$USER/.npm

# Create Laravel storage directories with proper permissions
RUN mkdir -p /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

# Set proper permissions for Laravel directories
# www-data needs write access for web server operations
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Add the user to www-data group to allow both user and web server to write
RUN usermod -a -G www-data $USER

# Create a script to fix permissions at container startup
RUN echo '#!/bin/bash\n\
if [ -d "/var/www/html/storage" ]; then\n\
  chmod -R 775 /var/www/html/storage\n\
  chown -R www-data:www-data /var/www/html/storage\n\
fi\n\
if [ -d "/var/www/html/bootstrap/cache" ]; then\n\
  chmod -R 775 /var/www/html/bootstrap/cache\n\
  chown -R www-data:www-data /var/www/html/bootstrap/cache\n\
fi\n\
exec "$@"' > /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Switch to user
USER $USER

# Set up Vite environment variables
ENV VITE_SERVER_HOST=0.0.0.0
ENV VITE_SERVER_PORT=5173
ENV VITE_HMR_HOST=localhost

# Add npm bin to PATH
ENV PATH /var/www/html/node_modules/.bin:$PATH

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
