# === Build Stage ===
FROM php:8.2-cli AS build

WORKDIR /app
COPY . .

# ビルドに必要な依存関係のみをインストール
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# === Run Stage ===
FROM php:8.2-apache

# 実行に必要なライブラリをインストール
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 1. Cloud Run のデフォルトポート 8080 に対応させる
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 2. Apache のドキュメントルートを調整
# プロジェクトの構造に合わせて /var/www/html か、その下の public ディレクトリを指定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 3. Apache の mod_rewrite を有効化（Laravelなどを使う場合に必要）
RUN a2enmod rewrite

# ソースコードのコピー
COPY --from=build /app /var/www/html

# 権限の設定（Apacheユーザーが書き込めるようにする）
RUN chown -R www-data:www-data /var/www/html

# Cloud Run は 8080 を期待する
EXPOSE 8080