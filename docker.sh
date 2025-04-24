#!/bin/bash

composer update
npm install --force && npm run build
php bin/console doctrine:migrations:migrate
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
# composer require doctrine/doctrine-fixtures-bundle

# php bin/console app:import-cities
# php bin/console doctrine:fixtures:load --purge-exclusions=city --purge-exclusions=department --no-interaction
# composer remove symfony/apache-pack
# composer require symfony/apache-pack -y
exec apache2-foreground
# exec make db_start_datas
