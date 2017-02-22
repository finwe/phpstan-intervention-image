# Intervention\Image extensions for PHPStan

[![Build Status](https://travis-ci.org/finwe/phpstan-intervention-image.svg)](https://travis-ci.org/finwe/phpstan-intervention-image)
[![Latest Stable Version](https://poser.pugx.org/finwe/phpstan-intervention-image/v/stable)](https://packagist.org/packages/finwe/phpstan-intervention-image)
[![License](https://poser.pugx.org/finwe/phpstan-intervention-image/license)](https://packagist.org/packages/finwe/phpstan-intervention-image)

* [PHPStan](https://github.com/phpstan/phpstan)
* [Intervention\Image](http://image.intervention.io/)

This extension provides following features:

* Provides definitions for magic `Intervention\Image\Image` methods

## Usage

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev finwe/phpstan-intervention-image
```

And include extension.neon in your project's PHPStan config:

```
includes:
	- vendor/finwe/phpstan-intervention-image/extension.neon
```
