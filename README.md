# How to run application

```bash
# Extra config to bind ports
cp docker-compose.override.example.yml docker-compose.override.yml
# run docker composer services
docker-compose up -d
# exec php container
docker-compose exec php bash
# install dependencies inside `php` container
composer install
# run DB migrations
bin/console doctrine:migration:migrate
# run console inside `php` container
bin/console
```

# How to run web server

```bash
symfony serve -d
```

API Docs http://localhost:8000/api

# How to change user ID in php docker container

There are a few options

- add ENVs in `.env`
```bash
DOCKER_COMPOSE__USER_ID=1000
DOCKER_COMPOSE__GROUP_ID=1000
```
- export ENVs
```bash
export DOCKER_COMPOSE__USER_ID=1000
export DOCKER_COMPOSE__GROUP_ID=1000
```

You need to rebuild docker images to apply new user ID
```bash
# Stop containers
docker-compose down
# Build docker images
docker-compose build
```

# Example how application works

```bash
student@family-budget[docker][/app]: bin/console f:w:c Cash USD -c -vvv

 [OK] New Wallet #4

 [OK] With income categories:
      Award
      Gift
      Salary
      Selling
      Other Income

 [OK] With expense categories:
      Food & Beverage
      Education
      Entertainment
      Gifts & Donations
      Health & Fitness
      Shopping
      Fees & Charges
      Transportation
      Other Expense
```
```bash
student@family-budget[docker][/app]: bin/console wallet:add-category 'Online Services'

 Choice wallet:
  [4] Cash, USD
  [6] Cash, UAH
 > 6

 Choice category type:
  [0] income
  [1] expense
 > expense

 [OK] Done
```

# PHPStorm plugins

- https://plugins.jetbrains.com/plugin/7219-symfony-support

# Useful links

- https://api-platform.com/docs/
- https://getcomposer.org/
- https://www.php.net/supported-versions
- https://symfony.com/doc/current/index.html
- https://hub.docker.com/_/php
