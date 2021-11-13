FROM php:7.3.31-cli-buster

#BASE
ENV TZ=Europe/Kiev

RUN apt-get update -y \
    && apt-get install -y  \
        git \
        zip \
        libicu-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
        intl \
        pcntl \
    && pecl install apcu \
       && pecl install apcu_bc \
       && docker-php-ext-enable --ini-name 0-apc.ini apcu apc \
    && docker-php-ext-install pdo pdo_mysql \
    && echo "Cleanup" \
        && apt-get clean \
        && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && curl -sS https://get.symfony.com/cli/installer | bash \
        && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Install Composer
COPY --from=composer:2.1.9 /usr/bin/composer /usr/bin/composer
#END BASE

#DEV
RUN echo "Install php-xdebug" \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd -g ${GROUP_ID} student \
    && useradd -m student -u ${USER_ID} -g student \
    && mkdir /app \
    && chown student:student /app
