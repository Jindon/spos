#!/bin/sh

echo "Deploying application ..."

sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
# sudo chown -R $USER:www-data storage/app/public/tmp

# Enter maintenance mode
php artisan down
    # Update codebase
    git pull origin main

    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Install node dependencies and build
    npm install
    npm run build

    # Migrate database
    php artisan migrate --force
    php artisan db:seed --class=StateSeeder --force

    # Note: If you're using queue workers, this is the place to restart them.
    # ...

    # Clear cache
    php artisan optimize

    # Reload PHP to update opcache
    echo "" | sudo -S service php8.0-fpm reload
# Exit maintenance mode
php artisan up

echo "Application deployed!"
