Slender Configurator
===
[![Latest Stable Version](https://poser.pugx.org/slender/configurator/v/stable.svg)](https://packagist.org/packages/slender/configurator) [![Total Downloads](https://poser.pugx.org/slender/configurator/downloads.svg)](https://packagist.org/packages/slender/configurator) [![License](https://poser.pugx.org/slender/configurator/license.svg)](https://packagist.org/packages/slender/configurator)

[![Build Status](https://travis-ci.org/alanpich/Slender-Configurator.svg?branch=develop)](https://travis-ci.org/alanpich/Slender-Configurator) [![Coverage Status](https://coveralls.io/repos/alanpich/Slender-Configurator/badge.png?branch=develop)](https://coveralls.io/r/alanpich/Slender-Configurator?branch=develop) 

---

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
$configurator = new Configurator\Configurator($PROJECT_ROOT, $ENVIRONMENT);


///////////////////////////////////////////////////////////////////////////////
//  Add the adaptors to load the type of files we want
///////////////////////////////////////////////////////////////////////////////
$configurator
    ->addAdaptor(new FileTypeAdapter\ArrayAdapter())
    ->addAdaptor(new FileTypeAdapter\JsonAdapter())
    ->addAdaptor(new FileTypeAdapter\IniAdapter())
    ->addAdaptor(new FileTypeAdapter\YamlAdapter());


///////////////////////////////////////////////////////////////////////////////
//  Add the paths that we want to load from
///////////////////////////////////////////////////////////////////////////////
$configurator
    ->addDirectory('/absolute/path/to/folder')
    ->addDirectory('./config/core')
    ->addDirectory('./config/app')
    ->addDirectory('./config/{ENVIRONMENT}'))
    

///////////////////////////////////////////////////////////////////////////////
//  Load the config files
///////////////////////////////////////////////////////////////////////////////
$configurator->load();



///////////////////////////////////////////////////////////////////////////////
//  Access the data
///////////////////////////////////////////////////////////////////////////////
print_r($configurator->toArray());

```
