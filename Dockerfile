FROM php:8.2-apache
WORKDIR /var/www/
COPY . .

RUN chown www-data:www-data /var/www/
RUN chmod 755 /var/www/

EXPOSE 80