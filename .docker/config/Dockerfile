FROM php:5.5-cli
MAINTAINER Meritoo <github@meritoo.pl>

#
# Tools & libraries
#
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        vim \
        git \
        unzip \
        libicu-dev \
        locales \
    && apt-get clean \
    && rm -rf \
        /var/lib/apt/lists/* \
        /tmp/* \
        /var/tmp/*

#
# Generating locales:
# - de_DE
# - es_ES
# - en_GB
# - en_US
# - fr_FR
# - it_IT
# - pl_PL
# - ru_RU
#
RUN sed -i 's/^# de_DE/de_DE/g; \
            s/^# es_ES/es_ES/g; \
            s/^# en_GB/en_GB/g; \
            s/^# en_US/en_US/g; \
            s/^# fr_FR/fr_FR/g; \
            s/^# it_IT/it_IT/g; \
            s/^# pl_PL/pl_PL/g; \
            s/^# ru_RU/ru_RU/g;' /etc/locale.gen \
    && locale-gen

#
# PHP extensions
#
RUN docker-php-ext-install \
    intl \
    mbstring

#
# PHP extensions (PECL):
# - Xdebug
#
RUN pecl install \
        xdebug \
    && docker-php-ext-enable \
        xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#
# PHP configuration:
# - default configuration
# - timezone
#
COPY php.ini /usr/local/etc/php/php.ini
ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"
#RUN echo "\n""date.timezone = $TIMEZONE""\n" >> /usr/local/etc/php/php.ini

#
# Phing
#
RUN pear channel-discover pear.phing.info \
    && pear install [--alldeps] phing/phing

#
# Composer - environment variables:
# - disable warning about running commands as root/super user
# - disable automatic clearing of sudo sessions
#
# More:
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
#
ENV COMPOSER_ALLOW_SUPERUSER 1

#
# Composer + https://packagist.org/packages/hirak/prestissimo package
#
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === \
        '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo \
        'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer global require \
        --no-plugins \
        --no-scripts \
        --no-progress \
        --no-suggest \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --classmap-authoritative \
        hirak/prestissimo \
    && rm -rf ~/.composer/cache/* \
    && composer clear-cache \
    && composer --version

#
# Bash
#
RUN sed -i 's/^# export/export/g' ~/.bashrc \
    && sed -i 's/^# alias/alias/g' ~/.bashrc \
    && echo "\n"'export PATH=/project/vendor/bin:$PATH'"\n" >> ~/.bashrc
