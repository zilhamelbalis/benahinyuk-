
  # Multi-stage build for optimized image size
  FROM php:8.2-apache AS builder
  
  # Install PHP extensions
  RUN apt-get update && apt-get install -y --no-install-recommends \
      libzip-dev \
      && docker-php-ext-install \
         mysqli \
         pdo \
         pdo_mysql \
      && a2enmod rewrite headers \
      && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
  
  # Final runtime stage
  FROM php:8.2-apache
  
  # Install runtime dependencies needed for mysqli
  RUN apt-get update && apt-get install -y --no-install-recommends \
      curl \
      && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
  
  # Install PHP extensions directly in final stage
  RUN docker-php-ext-install mysqli pdo pdo_mysql
  
  # Enable Apache modules
  RUN a2enmod rewrite headers && a2dismod status || true
  
  # Security hardening
  RUN echo 'ServerTokens Prod' >> /etc/apache2/apache2.conf && \
      echo 'ServerSignature Off' >> /etc/apache2/apache2.conf && \
      echo 'Header always unset X-Powered-By' >> /etc/apache2/apache2.conf && \
      echo 'Header unset X-Powered-By' >> /etc/apache2/apache2.conf
  
  # Copy application code
  COPY --chown=www-data:www-data . /var/www/html/
  WORKDIR /var/www/html
  
  # Create necessary directories with proper permissions
  RUN mkdir -p /var/www/html/uploads /var/www/html/logs && \
      chown -R www-data:www-data /var/www/html && \
      chmod -R 755 /var/www/html && \
      chmod -R 775 /var/www/html/uploads && \
      chmod -R 775 /var/www/html/logs
  
  # Note: .env file not needed in production - using K8s environment variables
  # COPY --chown=www-data:www-data .env.example* ./
  # RUN if [ -f .env.example ] && [ ! -f .env ]; then cp .env.example .env; fi
  
  # Health check
  HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
      CMD curl -f http://localhost/ || exit 1
  
  EXPOSE 80
  CMD ["apache2-foreground"]
  