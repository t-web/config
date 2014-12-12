<?php
use Slender\Configurator\CacheHandler\FileCacheHandler;
use Slender\Configurator\Config;
use Slender\Configurator\FileTypeAdapter;

require dirname(__DIR__).'/vendor/autoload.php';


$configurator = new Config();
$configCacheHandler = new FileCacheHandler(dirname(__FILE__).'/config.cache');

$configurator
    ->setRootPath(dirname(__FILE__))
    ->setEnvironment("development")
//    ->setCacheHandler($configCacheHandler)
    ->addAdapter(new FileTypeAdapter\ArrayAdapter())
    ->addAdapter(new FileTypeAdapter\JsonAdapter())
    ->addAdapter(new FileTypeAdapter\IniAdapter())
    ->addAdapter(new FileTypeAdapter\YamlAdapter());

$configurator
    ->addDirectory('./config')
    ->addDirectory('./config/{ENVIRONMENT}')
    ->finalize();



print_r($configurator->toArray());
