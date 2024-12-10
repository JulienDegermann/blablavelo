SHELL := /bin/bash

SYMFONY = symfony console

# FOR DEV ENV

db_delete:
	$(SYMFONY) doctrine:database:drop --force --if-exists --no-interaction
.PHONY: db_delete

db_create:
	 $(SYMFONY) doctrine:database:create
.PHONY: db_create

new_migration:
	$(SYMFONY) make:migration
.PHONY: new_migration

db_migrate:
	$(SYMFONY) doctrine:migrations:migrate --no-interaction
.PHONY: db_migrate

cities:
	$(SYMFONY) app:import-cities
.PHONY: cities

load_fixtures:
	$(SYMFONY) doctrine:fixtures:load --purge-exclusions=city --purge-exclusions=department --no-interaction
.PHONY: fixtures

db_start:
	make db_delete
	make db_create
	make db_migrate
.PHONY: db_start

start:
	make cache
	symfony server:start -d
.PHONY: db_start

stop:
	symfony server:stop
.PHONY: stop

restart:
	make stop
	make start
.PHONY: restart

db_update:
	make new_migration
	make db_migrate
.PHONY: db_update

datas:
	make cities
	make load_fixtures
.PHONY: datas

db_start_datas:
	make db_start
	make datas
.PHONY: db_start_datas

db_reset:
	make db_delete
	make db_create
	make new_migration
	make db_migrate
.PHONY: db_reset

db_reset_datas:
	make db_reset
	make datas
.PHONY: db_reset_datas

cache:
	$(SYMFONY) cache:clear
.PHONY: cache

compile:
	npm run dev
	make cache
.PHONY: compile_dev


docker_reset:
	docker-compose down
	docker build -t blablavelo .
	docker-compose up -d
.PHONY: docker_reset

docker_start:
	docker-compose up -d
.PHONY: docker_start

docker_stop:
	docker-compose down
.PHONY: docker_stop

docker_restart:
	docker-compose down
	docker-compose up -d
.PHONY: docker_restart

docker_bash:
	docker exec -it blablavelo_php8.3 bash
.PHONY: docker_bash



# FOR PROD ENV
build:
	npm run build
	make cache
.PHONY: build


