# Sandbox

Symfony project for testing purposes

````bash
cd infra/docker
docker-compose build
docker-compose up -d
docker exec -it sandbox-php composer install
````

```bash
cd infra/docker
docker build -t sandbox-node node/ --no-cache
cd ../../
docker run -it -v $(pwd):/home/app sandbox-node bash
```
