# cordo-bundle-backoffice

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Backoffice bundle for [Cordo microframework](https://github.com/darkorsa/cordo)

## Install

Go to your [Cordo](https://github.com/darkorsa/cordo) project folder root dir and type:

``` bash
$ composer require darkorsa/cordo-bundle-backoffice
```

Next register bundle install command in `./cordo` file:

``` php
$application->add(new \Cordo\Bundle\Backoffice\InstallCommand($container));
```

and execute command:

``` bash
$ php cordo cordo/backoffice:install
```

That will install all the modules in your `./app/Backoffice` folder, update `./app/Register.php` file and create the DB schemas. 

If you want to change to default installation folder, provide context parameter:

``` bash
$ php cordo cordo/backoffice:install MyContext
```

That will change the installation folder to `./app/MyContext`.

Great! Backoffice bundle now is installed and ready to use.

## Security

If you discover any security related issues, please email dkorsak@gmail.com instead of using the issue tracker.

## Credits

- [Dariusz Korsak][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/darkorsa/cordo-bundle-backoffice.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/darkorsa/cordo-bundle-backoffice/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/darkorsa/cordo-bundle-backoffice.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/darkorsa/cordo-bundle-backoffice.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/darkorsa/cordo-bundle-backoffice.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/darkorsa/cordo-bundle-backoffice
[link-travis]: https://travis-ci.org/darkorsa/cordo-bundle-backoffice
[link-scrutinizer]: https://scrutinizer-ci.com/g/darkorsa/cordo-bundle-backoffice/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/darkorsa/cordo-bundle-backoffice
[link-downloads]: https://packagist.org/packages/darkorsa/cordo-bundle-backoffice
[link-author]: https://github.com/darkorsa
[link-contributors]: ../../contributors
