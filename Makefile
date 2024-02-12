ARGS=$(filter-out $@, $(MAKECMDGOALS))

init: .docker-build .composer-install .migrate
rebuild: .docker-down init
console: .docker-up .docker-console
fixtures: .fixtures

.docker-up:
	docker-compose up

.docker-down:
	docker-compose down

.docker-build:
	docker-compose build

.docker-console:
	docker-compose run -it php bash

.composer-install:
	docker-compose run -it php composer install

.migrate:
	docker-compose run -it php bin/console doctrine:migrations:migrate --no-interaction

.fixtures:
	docker-compose run -it php ./bin/console doctrine:fixtures:load --no-interaction --no-debug
