# === Build Stage ===
FROM php:8.2-cli AS build
WORKDIR /app
COPY . .

# 必要に応じてComposerを使って依存関係をインストール
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev

# === Run Stage ===
FROM php:8.2-apache
WORKDIR /app
COPY --from=build /app /var/www/html
EXPOSE 80
