# === Build Stage ===
FROM php:8.2-cli AS build

# 必要なPHP拡張をインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip curl intl mbstring

WORKDIR /app
COPY . .

# Composerをインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Composerのインストール確認
RUN composer --version

# Composerのキャッシュをクリアしてから依存関係をインストール
RUN composer clear-cache && composer install --no-dev --no-progress --verbose

# === Run Stage ===
FROM php:8.2-apache

# Apacheで動作させる
WORKDIR /app
COPY --from=build /app /var/www/html

# Apacheの設定（必要であれば、.htaccessやその他設定を追加する）
EXPOSE 80
