# up -> Start the Docker containers
up:
	docker compose up -d

# down -> Stop the Docker containers
down:
	docker compose down -v

# migrate -> Run database migrations
migrate:
	docker compose exec app php artisan migrate --force

# seed -> Seed the database
seed:
	docker compose exec app php artisan db:seed --force

# refresh -> Refresh the database and run all migrations
refresh:
	docker compose exec app php artisan migrate:refresh --force

# test -> Run the application tests
test:
	docker compose exec app php artisan test