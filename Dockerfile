# === Build Stage ===
FROM php:8.2-cli AS build
WORKDIR /app
# 1. ソースコードをコピー
COPY . .
# 2. 必要なライブラリのみインストール
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# === Run Stage ===
FROM php:8.2-apache

# 3. 実行に必要なライブラリをインストール
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 4. ポート 8080 への変更（Cloud Run 必須設定）
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 5. ドキュメントルートの設定（重要）
# index.php がプロジェクト直下にある場合は /var/www/html
# public/index.php のように public フォルダ内にある場合は /var/www/html/public にしてください
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf

# 6. Apache mod_rewrite の有効化
RUN a2enmod rewrite

# 7. ファイルのコピー（/app の「中身」を html 直下に展開）
WORKDIR /var/www/html
COPY --from=build /app/ .

# 8. 権限設定
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
