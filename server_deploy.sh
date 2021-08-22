#!/bin/sh
set -e

echo "Deploying application ..."

sudo chmod -R 775 storage

# Enter maintenance mode
(php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    # Update codebase
    git pull origin main

    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Install node dependencies and build
    yarn
    yarn build

    # Migrate database
    php artisan migrate --force
    php artisan db:seed --class=StateSeeder

    # Note: If you're using queue workers, this is the place to restart them.
    # ...

    # Clear cache
    php artisan optimize

    # Reload PHP to update opcache
    echo "" | sudo -S service php8.0-fpm reload
# Exit maintenance mode
php artisan up

echo "Application deployed!"
