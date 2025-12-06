.PHONY: help install up down restart build shell composer migrate seed test

help: ## Muestra esta ayuda
	@echo "Comandos disponibles:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

install: ## Instala el proyecto completo
	cp .env.example .env
	docker-compose up -d --build
	docker-compose exec app composer install
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate
	@echo "✅ Proyecto instalado correctamente"

up: ## Levanta los contenedores
	docker-compose up -d

down: ## Detiene los contenedores
	docker-compose down

restart: ## Reinicia los contenedores
	docker-compose restart

build: ## Construye las imágenes
	docker-compose build

shell: ## Accede al shell del contenedor app
	docker-compose exec app bash

composer: ## Ejecuta composer install
	docker-compose exec app composer install

migrate: ## Ejecuta las migraciones
	docker-compose exec app php artisan migrate

seed: ## Ejecuta los seeders
	docker-compose exec app php artisan db:seed

test: ## Ejecuta los tests
	docker-compose exec app php artisan test

clear: ## Limpia las cachés
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

logs: ## Muestra los logs
	docker-compose logs -f









