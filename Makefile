PHP_CONTAINER = sandbox-php
DATABASE_CONTAINER = sandbox-db
DATABASE_NAME = sandbox
DATABASE_USER = sandbox
DATABASE_PASSWORD = sandbox

docker-setup:
        cd infra/docker
        docker-compose build
        docker-compose up
        cd ../../

composer-install:
        docker exec -it ${PHP_CONTAINER} composer install

database-setup:
        docker exec -t ${DATABASE_CONTAINER} mysql -e "CREATE DATABASE IF NOT EXISTS ${DATABASE_NAME}"
        docker exec -t ${DATABASE_CONTAINER} mysql -e "GRANT ALL ON ${DATABASE_NAME}.* TO '${DATABASE_USER}'@'%' IDENTIFIED BY '${DATABASE_PASSWORD}'"
