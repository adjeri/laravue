<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Backend Laravel Frontend Vue
## Testing some vue packages

# How to get the project running on your computer

Please do the following:

## Requirements

* Install WAMP or XAMPP or Laragon (my favorite on Windows) for the local server
* Install [Composer](https://getcomposer.org/download/)
* Install [npm](https://nodejs.org/en/download/)

Add composer, and npm them to the PATH if not done automatically.

Test composer and npm in your terminal to make sure they work.


## Install Laravel and Vue packages

Browse to the project folder from your terminal.

At the root of the project folder, run the following commands:

* To download the necessary Laravel packages for the backend:


```
composer install
```

* To download the necessary Vue packages for the frontend:

```
npm install
```


## Set up the environment file for your computer

Copy the .env.debug file into .env or run the command below :

```
ln -s .env.debug .env
```

Open the .env file and edit it according to the configuration of your computer.

In your terminal, run the next command to create the tables in the database:

```
php artisan migrate
```

## Running the project

Depending on the configuration of your system, to run the project, execute

```
php artisan serve
```

Please, check the response in your console after running the command to have the display in your browser.

For the frontend, run

```
npm run watch
```


*Happy dev* 😉

