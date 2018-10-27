FROM php:5.6-cli
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
# Set default language
#
# Required to avoid problem with using strange language by error messages.
# Example: "chmod(): Aucun fichier ou dossier de ce type"
#
ENV LANGUAGE=en_US.UTF-8

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
        xdebug-2.5.5 \
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
    && printf '[PHP]\ndate.timezone = "%s"\n' ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
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
        '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo \
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
RUN sed -i 's/^# export/export/g; \
            s/^# alias/alias/g;' ~/.bashrc \
    && echo 'COLUMNS=200'"\n" >> ~/.bashrc

#
# Use project-related binaries globally
#
ENV PATH="/project/vendor/bin:${PATH}"

WORKDIR /project
