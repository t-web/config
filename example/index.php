<?php
use Slender\Config\CacheHandler\FileCacheHandler;
use Slender\Config\Config;
use Slender\Config\FileTypeAdapter;

require dirname(__DIR__).'/vendor/autoload.php';


$config = new Config();
$configCacheHandler = new FileCacheHandler(dirname(__FILE__).'/config.cache');

$config->setRootPath(dirname(__FILE__))
    ->setEnvironment("development")
//    ->setCacheHandler($configCacheHandler)
    ->addAdapter(new FileTypeAdapter\ArrayAdapter())
    ->addAdapter(new FileTypeAdapter\JsonAdapter())
    ->addAdapter(new FileTypeAdapter\IniAdapter())
    ->addAdapter(new FileTypeAdapter\YamlAdapter());

$config->addDirectory('./config')
    ->addDirectory('./config/{ENVIRONMENT}')
    ->finalize();



print_r($config->toArray());
