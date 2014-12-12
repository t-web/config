<?php
use Slender\Configurator\CacheHandler\FileCacheHandler;
use Slender\Configurator\Configurator;
use Slender\Configurator\FileTypeAdapter;

require dirname(__DIR__).'/vendor/autoload.php';


$start = microtime(true);

$configurator = new Configurator();



$configurator
    ->setRootPath(dirname(__FILE__))
    ->setEnvironment("development")
    ->setCacheHandler(new FileCacheHandler( dirname(__FILE__).'/config.cache'))
    ->addAdapter(new FileTypeAdapter\ArrayAdapter())
    ->addAdapter(new FileTypeAdapter\JsonAdapter())
    ->addAdapter(new FileTypeAdapter\IniAdapter())
    ->addAdapter(new FileTypeAdapter\YamlAdapter());


$configurator
    ->addDirectory('./config')
    ->addDirectory('./config/{ENVIRONMENT}')
    ->finalize();


$totalTime = microtime(true) - $start;
echo "Configuration loaded in {$totalTime}s\n";

print_r($configurator->toArray());
