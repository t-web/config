<?php
use Slender\Configurator\Configurator;
use Slender\Configurator\FileTypeAdapter;

require dirname(__DIR__).'/vendor/autoload.php';

$configurator = new Configurator();

$configurator
    ->setRootPath(dirname(__FILE__))
    ->setEnvironment("development")
    ->addAdapter(new FileTypeAdapter\ArrayAdapter())
    ->addAdapter(new FileTypeAdapter\JsonAdapter())
    ->addAdapter(new FileTypeAdapter\IniAdapter())
    ->addAdapter(new FileTypeAdapter\YamlAdapter());


$configurator
    ->addDirectory('./config')
    ->addDirectory('./config/{ENVIRONMENT}');


$configurator->load();

print_r($configurator->toArray());
