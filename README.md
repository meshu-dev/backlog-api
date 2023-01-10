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
-  Run DB migrations
```
php bin/console doctrine:migrations:migrate
```
-  Run data fixtures for test data
```
php bin/console doctrine:fixtures:load
```
-  Run on local environment
```
composer run dev
```
