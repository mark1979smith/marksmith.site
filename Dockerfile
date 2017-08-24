FROM php-zendserver:latest

# Set the working directory to /app
WORKDIR /var/www/html

RUN touch index2.php
