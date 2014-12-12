Slender Configurator
===
[![Latest Stable Version](https://poser.pugx.org/slender/configurator/v/stable.svg)](https://packagist.org/packages/slender/configurator) [![Total Downloads](https://poser.pugx.org/slender/configurator/downloads.svg)](https://packagist.org/packages/slender/configurator) [![License](https://poser.pugx.org/slender/configurator/license.svg)](https://packagist.org/packages/slender/configurator)

[![Build Status](https://travis-ci.org/alanpich/Slender-Configurator.svg?branch=develop)](https://travis-ci.org/alanpich/Slender-Configurator) [![Coverage Status](https://coveralls.io/repos/alanpich/Slender-Configurator/badge.png?branch=develop)](https://coveralls.io/r/alanpich/Slender-Configurator?branch=develop) 

---
A fast, simple multi-format configuration loader with __no dependencies*__. 

Allows you to load configurations from multiple directories, and in several different formats including _PHP Arrays_, _JSON_, _YAML_, and _INI_. Directory path placeholders allow you to assign dynamic paths based on environment or location, and an optional caching mechanism can speed up loading exponentially.


\* YAML parsing requires `symfony/yaml`

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

$config = Slender\Configurator\Config(dirname(__FILE__));
$config->addAdaptor(new Slender\Configurator\FileTypeAdapter\ArrayAdapter());
$config->addDirectory('./config');
$config->load();
$settings = $config->toArray();
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
$config = new Configurator\Config($PROJECT_ROOT, $ENVIRONMENT);

///////////////////////////////////////////////////////////////////////////////
//  Add a cache handler
//    - Adding a cache handler means that the entire, merged configuration 
//      can be cached once it is prepared, greatly speeding up future loads.
///////////////////////////////////////////////////////////////////////////////
$config->setCacheHandler(new Configurator\CacheHandler\FileCacheHandler(
		$PROJECT_ROOT.'/config.cache'
	));


///////////////////////////////////////////////////////////////////////////////
//  Add the adaptors to load the type of files we want
///////////////////////////////////////////////////////////////////////////////
$config
    ->addAdaptor(new FileTypeAdapter\ArrayAdapter())
    ->addAdaptor(new FileTypeAdapter\JsonAdapter())
    ->addAdaptor(new FileTypeAdapter\IniAdapter())
    ->addAdaptor(new FileTypeAdapter\YamlAdapter());


///////////////////////////////////////////////////////////////////////////////
//  Add the paths that we want to load from,
//  then `finalize` the configuration to signal that
//  the current config should be cached.
///////////////////////////////////////////////////////////////////////////////
$config
    ->addDirectory('/absolute/path/to/folder')
    ->addDirectory('./config/core')
    ->addDirectory('./config/app')
    ->addDirectory('./config/{ENVIRONMENT}'))
    ->finalize();


///////////////////////////////////////////////////////////////////////////////
//  Access the data
///////////////////////////////////////////////////////////////////////////////
print_r($config->toArray());

```



## Caching Configurations for speed
Loading configurations from multiple files and merging their values on every page load can be a costly and time-consuming exercise for your script. To avoid this weight on your server, you ~~can~~ should cache your app's configuration on the first load, and use the cached version on future loads.

To activate cacheing for your Config instance, you need to pass an implementation of `CacheHandlerInterface` to your config's `setCacheHandler()` method. When you do this, it will automatically try to load a configuration from the cache, and disable any file loading and parsing until the `finalize()` method is called. 

#### Example
```php
<?php
use Slender\Configurator;

// Your config instance
$config = new Configurator\Config();

// Create the CacheHandler to manage caching
$cacher = new Configurator\CacheHandler\FileCacheHandler("/path/to/cache.file");

// Add your adapters as normal. They will only
// be used on a 'miss' run - i.e. when there is no cached value.
$config->addAdapter(/* ... */);
 
$config->setCacheHandler($cacher);
	// At this point, the CacheHandler will look for a
	// file at `/path/to/cache.file`, and if it exists 
	// it will be loaded and the value set as the current 
	// configuration. Additional directory loading will
	// be disabled.

$config->addDirectory('/path/to/foo');
	// If the cache exists, then this directory will not be scanned 
	// on this run.

$config->finalize();
	// This unlocks the caching again. If this is a 'miss' run (not cached)
	// then the current configuration is sent to the CacheHandler for storage.
	
$config->addDirectory('/path/to/bar');
	// This directory will be scanned for config files, and the values
	// will be merged into the configuration, but the additional values 
	// will NOT be cached. This is useful for runtime-specific configs.
```
