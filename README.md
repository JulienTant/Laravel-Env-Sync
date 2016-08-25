[![Travis](https://img.shields.io/travis/JulienTant/Laravel-Env-Sync.svg?maxAge=2592000)](https://travis-ci.org/JulienTant/Laravel-Env-Sync)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/JulienTant/Laravel-Env-Sync.svg?maxAge=2592000)](https://scrutinizer-ci.com/g/JulienTant/Laravel-Env-Sync/?branch=master)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/JulienTant/Laravel-Env-Sync.svg?maxAge=2592000)](https://travis-ci.org/JulienTant/Laravel-Env-Sync)

# Laravel Env Sync

Keep your .env in sync with your .env.example. It reads the .env.example file and propose you to fill your .env accordingly

## Installation via Composer

Start by requiring the package with composer

```
composer require jtant/laravel-env-sync
```

Then add the `\Jtant\LaravelEnvSync\EnvSyncServiceProvider::class` service provider to your `config/app.php` file, and that's it

## Usage

### Sync your envs files

You can sync your fill your .env file from the .env.example by using the `php artisan env:sync` command.

The command will tell you if there's anything not in sync between your files and will propose you to add values into the .env file.

You can launch the commande with the option `--reverse` to fill the .env.example file from the .env file

### Check for diff in your envs files

You can check if your .env is missing some variables from your .env.example by using the `php artisan env:check` command.

The command simply show you which keys are not present in your .env file. This command will return 0 if your files are in sync, and 1 if they are not, so you can use this in a script

Again, you can launch the commande with the option `--reverse`
