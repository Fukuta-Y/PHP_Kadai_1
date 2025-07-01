# === Build Stage ===
FROM php:8.2-cli AS build

WORKDIR /app
COPY . .

# 必要なPHP拡張をインストール
RUN apt-get update
RUN apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev

# 必要なPHP拡張をインストール
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# === Run Stage ===
FROM php:8.2-apache

# 必要なPHP拡張を再インストール（pdo_pgsql）
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql

# コンテナの作業ディレクトリを指定
WORKDIR /app
COPY --from=build /app /var/www/html

# Apacheで動作させる
EXPOSE 80
