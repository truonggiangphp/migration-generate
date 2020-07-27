# Laravel Migrations Generator

Generate Laravel Migrations from an existing database, including indexes and foreign keys!

##News

1. Major rewrite on `FieldGenerator` and `IndexGenerator`.
1. Fixed miscellaneous bugs.
1. Added `spatial` data type support such as `geometry`, `point`, etc.
1. Support more Laravel migration types such as `json`, `uuid`, `longText`, `year`, etc
1. Added `spatialIndex` support.
1. `timestamp` and `datetime` support precision.
1. Fixed MySQL `tinyInteger` and `boolean` issue.
1. Able generate `softDeletes`, `rememberToken`, `timestamps` types.
1. Support `set` for MySQL.
1. It is now possible to generate nullable `timestamp`
1. Removed unused classes.
1. Added UT!
1. More UT will be added to increase coverage.

This package is cloned from https://github.com/Xethron/migrations-generator and updated to support Laravel 6 and above.

## Version Compatibility

|Laravel|Version|
|---|---|
|7.x|4.x|
|6.x|4.x|
|5.8.x|4.x|
|5.7.x|4.x|
|5.6.x|4.x|
|5.5 and below|https://github.com/Xethron/migrations-generator|

## Install

The recommended way to install this is through composer:

```bash
composer require --dev "webikevn/migration-generate"
```

### Laravel Setup

Laravel will automatically register service provider for you.

### Lumen Setup

Auto discovery is not available in Lumen, you need some modification on `bootstrap/app.php`

#### Register provider

Add following line

```
$app->register(\Webike\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
```

## Usage

To generate migrations from a database, you need to have your database setup in Laravel's Config.

Run `php artisan migrate:generate` to create migrations for all the tables, or you can specify the tables you wish to generate using `php artisan migrate:generate table1,table2,table3,table4,table5`. You can also ignore tables with `--ignore="table3,table4,table5"`

Laravel Migrations Generator will first generate all the tables, columns and indexes, and afterwards setup all the foreign key constraints. So make sure you include all the tables listed in the foreign keys so that they are present when the foreign keys are created.

You can also specify the connection name if you are not using your default connection with `--connection="connection_name"`

Run `php artisan help migrate:generate` for a list of options.

|Options|Description|
|---|---|
|-c, --connection[=CONNECTION]|The database connection to use|
|-t, --tables[=TABLES]|A list of Tables you wish to Generate Migrations for separated by a comma: users,posts,comments|
|-i, --ignore[=IGNORE]|A list of Tables you wish to ignore, separated by a comma: users,posts,comments|
|-p, --path[=PATH]|Where should the file be created?|
|  --defaultIndexNames|Don't use db index names for migrations|
|  --defaultFKNames|Don't use db foreign key names for migrations|
|-tp, --templatePath[=TEMPLATEPATH]|The location of the template for this generator|

## Thank You

This package is cloned from https://github.com/Xethron/migrations-generator

Thanks to Jeffrey Way for his amazing Laravel-4-Generators package. This package depends greatly on his work.

## Contributors

Nguyen Giang (https://www.facebook.com/truonggiang.gk)

## License

The Laravel Migrations Generator is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
