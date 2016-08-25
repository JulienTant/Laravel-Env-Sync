! Ths package is still in active developement. Use at your own risks !

# Laravel Env Sync

Keep your .env in sync with your .env.example. It reads the .env.example file and propose you to fill your .env accordingly

## Installation via Composer

Start by requiring the package with composer

```
composer require jtant/laravel-env-sync
```

Then add the `\Jtant\LaravelEnvSync\EnvSyncServiceProvider::class` service provider to your `config/app.php` file, and that's it

## Usage

You can use this package by running the `php artisan env:sync` command.

The command will tell you if there's anything not in sync between your files and will propose you to add values into the .env file.

You can launch the commande with the option `--reverse` to fill the .env.example file from the .env file

## Future

- [ ] Add some tests
- [ ] Do some refactoring to decouple the package from artisan.
