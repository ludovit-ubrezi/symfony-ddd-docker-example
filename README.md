### Getting Started

- Install docker !!!
- Easiest option is Download Docker Desktop -> https://www.docker.com/ 

1. Docker setup - wait till all containers are running
```sh
cd docker_symfony 
docker-compose up -d --build
```

2. Run command to enter into docker console
```sh
docker-compose exec php /bin/bash
```

3. Run command to install packages
```sh
composer install
```

4. Update after composer.json changes
```sh
composer update
```

5. Run command to create migrations after updating Models/Entities
```sh
symfony console doctrine:migration:diff | symfony console doctrine:migrations:generate
```

6. Run command to run migration scripts 
```sh
symfony console doctrine:migrations:migrate
```


## First time run
- Proceed steps 1. 2. 3. 6.

- Go to http://localhost:8080/author/ and add one author
- in code are all Author linked to row id 1(first user) due to excluded login functionality
- not working without created user

- After that go to http://localhost:8080/post/ and do your magic
- all the links are self explanatory