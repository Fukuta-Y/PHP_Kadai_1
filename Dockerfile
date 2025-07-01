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

WORKDIR /app
COPY --from=build /app /var/www/html

# PostgreSQLのPDO拡張は通常デフォルトで有効化されているため、再度有効化は不要
# 必要であれば、インストールや有効化を行います。

# Apacheで動作させる
EXPOSE 80
