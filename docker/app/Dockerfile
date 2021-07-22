FROM php:8.0-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    zsh \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install xdebug
RUN pecl install xdebug-3.0.0 \
    && docker-php-ext-enable xdebug  
 
# Clear cache
RUN apt-get -y autoremove \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instal oh-my-zsh
ENV TERM xterm
ENV ZSH_THEME agnoster
RUN sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"