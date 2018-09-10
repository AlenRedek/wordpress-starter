FROM wordpress:4.9-php7.2-apache

ENV WP_ROOT /var/www/html/

WORKDIR $WP_ROOT

COPY wp-config.php .