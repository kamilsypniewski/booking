FROM php:7.4-apache


RUN a2enmod rewrite


# instalacja zip
RUN apt-get update && \
     apt-get install -y \
         libzip-dev \
         zlib1g-dev \
		 libpq-dev \
         && docker-php-ext-install zip

# instalacja GD
RUN apt-get install -y libpng-dev && \
	docker-php-ext-install gd

# instalacja soapClient
RUN rm /etc/apt/preferences.d/no-debian-php && \
    apt-get update -yqq && \
    apt-get -y install libxml2-dev php-soap && \
	docker-php-ext-install soap

# instalacja PGSQL
RUN docker-php-ext-install pdo pdo_pgsql


RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini


RUN echo "memory_limit = 2048M" >> /usr/local/etc/php/conf.d/memory-limit-php.ini
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/error-log.ini
RUN echo "error_log = /var/www/html/var/log/php_error.log" >> /usr/local/etc/php/conf.d/error-log.ini


RUN curl --silent --show-error https://getcomposer.org/installer | php \
  && mv ./composer.phar /bin/composer

RUN rm -rf /etc/apache2/sites-enabled/000-default.conf
COPY ./000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN service apache2 restart
