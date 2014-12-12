Slender Configurator
===
[![Latest Stable Version](https://poser.pugx.org/slender/configurator/v/stable.svg)](https://packagist.org/packages/slender/configurator) [![Total Downloads](https://poser.pugx.org/slender/configurator/downloads.svg)](https://packagist.org/packages/slender/configurator) [![License](https://poser.pugx.org/slender/configurator/license.svg)](https://packagist.org/packages/slender/configurator)

[![Build Status](https://travis-ci.org/alanpich/Slender-Configurator.svg?branch=develop)](https://travis-ci.org/alanpich/Slender-Configurator) [![Coverage Status](https://coveralls.io/repos/alanpich/Slender-Configurator/badge.png?branch=develop)](https://coveralls.io/r/alanpich/Slender-Configurator?branch=develop) 

---

## Installation

Install via composer:

```
composer require slender/configurator
```

## Usage

### Simple Setup

```php
<?php

$config = Slender\Configurator\Configurator(dirname(__FILE__));
$config->addAdaptor(new Slender\Configurator\FileTypeAdapter\ArrayAdapter());
$config->addDirectory('./config');
$config->load();
$config->toArray();
```

### Advanced Setup

```php
<?php

use Slender\Configurator;
use Slender\Configurator\FileTypeAdapter;

$PROJECT_ROOT = dirname(__FILE__);
$ENVIRONMENT = 'development';


///////////////////////////////////////////////////////////////////////////////
//  Create a configurator
//    - passing the optional string $basePath as first argument will allow
//      using relative paths to folders
///////////////////////////////////////////////////////////////////////////////
$config = new Configurator\Configurator($PROJECT_ROOT, $ENVIRONMENT);


///////////////////////////////////////////////////////////////////////////////
//  Add the adaptors to load the type of files we want
///////////////////////////////////////////////////////////////////////////////
$config
    ->addAdaptor(new FileTypeAdapter\ArrayAdapter())
    ->addAdaptor(new FileTypeAdapter\JsonAdapter())
    ->addAdaptor(new FileTypeAdapter\IniAdapter())
    ->addAdaptor(new FileTypeAdapter\YamlAdapter());


///////////////////////////////////////////////////////////////////////////////
//  Add the paths that we want to load from
///////////////////////////////////////////////////////////////////////////////
$config
    ->addDirectory('/absolute/path/to/folder')
    ->addDirectory('./config/core')
    ->addDirectory('./config/app')
    ->addDirectory('./config/{ENVIRONMENT}'))
    

///////////////////////////////////////////////////////////////////////////////
//  Load the config files
///////////////////////////////////////////////////////////////////////////////
$config->load();



///////////////////////////////////////////////////////////////////////////////
//  Access the data
///////////////////////////////////////////////////////////////////////////////
print_r($config->toArray());

```
