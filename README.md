# SyncWords Events API

### NOTE
This is not a full-blown application. Therefore, to be concise:
- The project was developed in a hurried state
- For authentication, only login was implemented 
- Docker was not used, therefore laravel sail is not utilized.

## Requirements
- PHP 8.1
- PostgreSQL
- Composer

## Installation
```bash
  git clone git@github.com:cheezytony/syncwords-assessment-test.git antonio-test && cd antonio-test
````
  OR

```bash
  git clone https://github.com/cheezytony/syncwords-assessment-test.git antonio-test && cd antonio-test
```

## Setup

### Database
Import the SQL file located in `./database/database.sql` into your sql client.

OR

Run the SQL code below
```SQL
-- Create application database
CREATE DATABASE syncwords_api;

-- Create database for testing
CREATE DATABASE syncwords_api_testing;

```

## Running the app

### Quick start
The project can be started quickly using the predefined shell script. 
````bash
./setup.sh
````

### Manual Start
````bash
# Create env file.
cp .env.example .env

# Install composer dependencies
composer install --ignore-platform-reqs

# Generate Laravel application key.
php artisan key:generate

# Migrate database and seed data
php artisan migrate --seed

# Start application
php artisan serve --port=8765
````

You can access the application using the URL below
````
http://localhost:8765
````

You can make api requests to the URL below
````
http://localhost:8765/api
````
### TEST CREDENTIALS
To authenticate requests you can use the default user below or any of the seeded users.
````
email: antonio.c.okoro@gmail.com
password: password
````

The passwords for the seeded users are set to `password` as well.

#### RUN TEST
````
php artisan test
````

#### STATIC ANALYSIS
````
php composer phpcs
php composer phpstan
````

## Features
1. Laravel 10
2. PHP 8.1
3. Tests - PHPUnit with **Code Coverage**
4. API Documentation - PostMan
5. Static Analysis - PHPStan, PHPCS
6. Application Database: PostgreSQl
7. Testing Database: PostgreSQl
8. Laravel Best Practices


### API Collection
[![Run in Postman](https://run.pstmn.io/button.svg)](https://documenter.getpostman.com/view/24160028/2s946mYpU5)

The authentication token is automatically added to the environment once a login request is successful
