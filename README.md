# Ecochain

This is a php frontend, which provides 

- simplistic blog interface
- API 
- search feature
- covered by integrational tests

# Install

- git pull https://github.com/ibudasov/ecochain.git
- composer install
- bin/console server:run
- bin/console assets:install
- @link: http://localhost:8000

# Install with Docker

- docker build -t ecochain .
- docker container run --name ecochain -e SYMFONY_ENV="dev" -d -p 80:8000 ecochain
- docker inspect ecochain # if you don't know your Docker IP
- @link: http://123.23.45.56

# Tests

- phpunit

# Further improvements

- Search algorithm could be better, you can find some todos in the source code.
- Security
- API docs
- Pictures?
