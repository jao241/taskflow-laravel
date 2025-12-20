# up -> Start the Docker containers
up:
	docker compose up -d

# down -> Stop the Docker containers
down:
	docker compose down

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

# test-coverage -> Run the application tests with coverage report
test-coverage:
	docker compose exec app php artisan test --coverage

# test-html-coverage -> Run the application tests with HTML coverage report, the files are in the coverage directory
test-html-coverage:
	docker compose exec app php artisan test --coverage-html coverage