## setup  
### with make  
- ```$ make init```
- ```$ make fixtures```
- ```$ make console```
### with docker-compose
- ```$ docker-compose up -d```
- ```$ docker-compose run -it php bash```
  - ```$ composer install```
  - ```$ ./bin/console doctrine:migrations:migrate --no-interaction```
  - ```$ ./bin/console doctrine:fixtures:load --no-interaction --no-debug```

## start (from container)
- run last test (by database id)
  - ```$ ./bin/console app:start-test```
- run without shuffle questions and answers
  - ```$ ./bin/console app:start-test --no-shuffle```
- select specific test by id (should exist in database)
  - ```$ ./bin/console app:start-test 2```

## info
- default test data stored in ```/src/DataFixtures/AppFixtures.php```
