# Backlog API

An API used to provide the data for the backlog website.

A Symfony app that connects to MySQL to retrieve and update data.

Built with PHP 8.1.14.

# Setup

- Install packages
```
composer install
```
-  Copy env file
```
cp .env.example .env
```
-  Open .env file and fill in required values
```
vim .env
```

## Generate JWT keys

- Fill in the following variables in .env for JWT Lexik library
```
JWT_SECRET_KEY
JWT_PUBLIC_KEY
```
- Generate Keys for JWT Lexik library
```
php bin/console lexik:jwt:generate-keypair
```

## Add DB tables and data

-  Run DB migrations
```
php bin/console doctrine:migrations:migrate
```
-  Run data fixtures for test data
```
php bin/console doctrine:fixtures:load
```

## Run development version

-  Run on local environment
```
composer run dev
```
