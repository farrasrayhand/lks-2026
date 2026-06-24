#!/bin/bash
set -e

# Clear all caches first to ensure clean state
rm -rf /var/www/bootstrap/cache/*.php

# Wait for MySQL
echo "Waiting for database connection..."
until php -r "try { new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'connected'; } catch (PDOException \$e) { exit(1); }" 2>/dev/null; do
    echo "Waiting for MySQL..."
    sleep 2
done

echo "Running migrations..."
php artisan migrate --force

# Only seed if users table is empty
USER_COUNT=$(php -r "try { \$pdo = new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo \$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(); } catch (Exception \$e) { echo '0'; }" 2>/dev/null | tr -d '[:space:]')
if [ "$USER_COUNT" = "0" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
else
    echo "Database already seeded ($USER_COUNT users), skipping."
fi

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
