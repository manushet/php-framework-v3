docker-up:
	docker-compose up --build -d

docker-exec-php:
	docker exec -it php-framework-v3_php_fpm_1 bash

docker-down:
	docker-compose down --remove-orphans