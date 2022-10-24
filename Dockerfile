FROM php:8.1-cli

WORKDIR /home/ubuntu/app

COPY . .

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    libgmp-dev \
    build-essential \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install gmp
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pdo_mysql

RUN composer install

CMD ["php", "index.php"]