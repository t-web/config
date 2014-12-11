<?php
use Slender\Configurator\Configurator;
use Slender\Configurator\FileTypeAdapter;

require dirname(__DIR__).'/vendor/autoload.php';


$configurator = new Configurator();

$configurator
    ->setRootPath(dirname(__FILE__))
    ->setEnvironment("development")
    ->addDirectory('./config')
    ->addDirectory('./config/{ENVIRONMENT}');

$configurator
    ->addAdapter( new FileTypeAdapter\PHP() )
    ->addAdapter( new FileTypeAdapter\JSON() )
    ->addAdapter( new FileTypeAdapter\INI() )
    ->addAdapter( new FileTypeAdapter\YAML() );




$configurator->load();


print_r($configurator->toArray());
