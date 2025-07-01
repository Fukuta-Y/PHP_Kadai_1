# === Build Stage ===
FROM php:8.2-cli AS build

WORKDIR /app
COPY . .

# 必要なPHP拡張があればインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# === Run Stage ===
FROM php:8.2-apache

WORKDIR /app
COPY --from=build /app /var/www/html

# Apacheで動作させる
EXPOSE 80
