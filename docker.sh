#!/bin/bash

set -x

composer update
npm install --force && npm run build
php bin/console doctrine:migrations:migrate
# php bin/console app:import-cities
php bin/console doctrine:fixtures:load --purge-exclusions=city --purge-exclusions=department --no-interaction
exec apache2-foreground
# exec make db_start_datas
