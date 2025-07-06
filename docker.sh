#!/bin/bash

composer install
npm install --force && npm run build
php bin/console doctrine:migrations:migrate
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

npm install --force && npm run build
npm run build

make rabbit_consumer

exec apache2-foreground
