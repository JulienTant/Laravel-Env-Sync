# Laravel Env Sync
Keep your .env in sync with your .env.example. It reads the .env.example file and propose you to fill your .env accordingly

## Installation via Composer

Start by requiring the package with composer
```
    composer require jtant/laravelenvsync
```

Then add the service provider to your `config/app.php` file, and that's it

## Usage

You can use this package by running the `php artisan env:sync` command.