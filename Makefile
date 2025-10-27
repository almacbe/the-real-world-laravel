.DEFAULT_GOAL := help

PHP      ?= php
ARTISAN  ?= $(PHP) artisan
COMPOSER ?= composer
PORT     ?= 8001
HOST     ?= 127.0.0.1

help: ## Show available targets
	@grep -E '^[a-zA-Z_-]+:.*?##' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?##"}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Install PHP dependencies, bootstrap app, and install JS deps
	$(COMPOSER) install
	@test -f .env || cp .env.example .env
	$(ARTISAN) key:generate --ansi
	$(ARTISAN) migrate --force --ansi
	$(ARTISAN) db:seed --force --ansi
	npm install

serve: ## Run development HTTP server on PORT (default 8001)
	$(ARTISAN) serve --host=$(HOST) --port=$(PORT)

migrate: ## Run database migrations
	$(ARTISAN) migrate --ansi

seed: ## Seed database with demo data
	$(ARTISAN) db:seed --ansi

cs: ## Run code style fixer (Pint)
	vendor/bin/pint

stan: ## Run static analysis (PHPStan)
	vendor/bin/phpstan analyse --memory-limit=1G

test: ## Run automated test suite
	$(ARTISAN) test

postman: ## Execute Newman collection against local server
	@$(ARTISAN) serve --host=$(HOST) --port=$(PORT) >/tmp/realworld-serve.log 2>&1 &
	@SERVER_PID=$$! ; \
	for i in $$(seq 1 30); do \
		nc -z $(HOST) $(PORT) >/dev/null 2>&1 && break ; \
		sleep 1 ; \
	done ; \
	npx --yes newman run tests/Postman/Conduit.postman_collection.json --env-var APIURL=http://$(HOST):$(PORT) ; \
	kill $$SERVER_PID ; \
	wait $$SERVER_PID 2>/dev/null || true
