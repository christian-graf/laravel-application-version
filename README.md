# Laravel Application Version

Fox's smarter application version manager :)

## Requirements

* PHP >= 7.2
* Composer >= 1.8
* Laravel Framework >= 5.7

## Installation

Use composer to install and use this package in your project.

Install them with

```bash
composer require "fox/laravel-application-version"
```

and you are ready to go!

### Laravel

The service provider will automatically get registered. Or you may manually add the service provider to your `config/app.php` file:

```php
'providers' => [
    // ...
    Fox\Application\Version\ServiceProvider::class,
];
```

## Usage

You can use the the provided classes directly for your own purpose.

### Version

```php
$myVersion = new Fox\Application\Version\Version(1, 0, 23, 'alpha');

echo $myVersion->major();
echo $myVersion->minor();
echo $myVersion->patch();
echo $myVersion->build();
echo (string) $myVersion;
```

This will generate the following output:

```
1
0
23
alpha
1.0.23-alpha
```

### Version Manager

You may also use the version manager to determine the current version of your application by parsing 
the composer.json file and run git to receive the last commit id of your working directory used as 
additional `build` information.

```
$versionManager = app('version-manager');
$myVersion = $versionManager->getCurrentVersion(); 
```

## Development - Getting Started

See the [CONTRIBUTING](CONTRIBUTING.md) file.

## Changelog

See the [CHANGELOG](CHANGELOG.md) file.

## License
 
See the [LICENSE](LICENSE.md) file.
