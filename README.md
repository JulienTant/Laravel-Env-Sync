[![Travis](https://img.shields.io/travis/JulienTant/Laravel-Env-Sync.svg?maxAge=3600)](https://travis-ci.org/JulienTant/Laravel-Env-Sync)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/JulienTant/Laravel-Env-Sync.svg?maxAge=3600)](https://scrutinizer-ci.com/g/JulienTant/Laravel-Env-Sync/?branch=master)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/JulienTant/Laravel-Env-Sync.svg?maxAge=3600)](https://scrutinizer-ci.com/g/JulienTant/Laravel-Env-Sync/?branch=master)

# Laravel Env Sync

Keep your .env in sync with your .env.example or vice versa.

It reads the .env.example file and makes suggestions to fill your .env accordingly.

## Installation via Composer

Start by requiring the package with composer

```
composer require jtant/laravel-env-sync
```


Then, if you use laravel < 5.5,  add the `Jtant\LaravelEnvSync\EnvSyncServiceProvider::class` service provider to your `config/app.php` file, and that's it

## Usage

### Sync your envs files

You can populate your .env file from the .env.example by using the `php artisan env:sync` command.

The command will tell you if there's anything not in sync between your files and will propose values to add into the .env file.

You can launch the command with the option `--reverse` to fill the .env.example file from the .env file

You can also use `--src` and `--dest` to specify which file you want to use. You must use either both flags, or none.

If you use the `--no-interaction` flag, the command will copy all new keys with their default values.

### Check for diff in your envs files

You can check if your .env is missing some variables from your .env.example by using the `php artisan env:check` command.

The command simply show you which keys are not present in your .env file. This command will return 0 if your files are in sync, and 1 if they are not, so you can use this in a script.

Again, you can launch the command with the option `--reverse` or with `--src` and `--dest`.

If you want to use the command after deployment to check, if any `.env` variables are missing, you can pass the `--notifySlack` flag. In case you are missing some variable message will be sent to slack using `ENV_SYNC_SLACK_URL`. You can also customize the channel using `ENV_SYNC_SLACK_CHANNEL` or you can publish all the configs with `php artisan vendor:publish` and customize them as you want.

### Show diff between your envs files

You can show a table that compares the content of your env files by using the `php artisan env:diff` command.

The command will print a table that compares the content of both .env and .env.example files, and will highlight the missing keys.

You can launch the command with the options `--src` and `--dest`.
