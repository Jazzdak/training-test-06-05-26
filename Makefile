.DEFAULT_GOAL := help
.PHONY: help install db serve start

CONSOLE = symfony console

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2}'

install: ## Install Composer dependencies
	symfony composer install

db: ## Run migrations and load fixtures into the SQLite database
	@test -f .env.local || { echo "Error: .env.local is required but does not exist." >&2; exit 1; }
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
	$(CONSOLE) foundry:load-fixtures --no-interaction

serve: ## Start the Symfony dev server
	symfony serve

start: install db serve ## Install dependencies, set up the database and start the dev server
