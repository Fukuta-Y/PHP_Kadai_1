# === Build Stage ===
FROM php:8.2-cli AS build

WORKDIR /app
COPY . .

# 必要なPHP拡張をインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \  # PostgreSQLのクライアントライブラリをインストール
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql  # PostgreSQL用PDO拡張をインストール

# === Run Stage ===
FROM php:8.2-apache

WORKDIR /app
COPY --from=build /app /var/www/html

# 必要なPHP拡張がインストールされていることを確認
RUN docker-php-ext-enable pdo pdo_pgsql  # PostgreSQLのPDO拡張を有効にする

# Apacheで動作させる
EXPOSE 80
