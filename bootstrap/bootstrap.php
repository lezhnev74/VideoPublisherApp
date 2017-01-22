<?php

//
// .ENV plugin
//
use Noodlehaus\Config;

$dotenv = new Dotenv\Dotenv(__DIR__ . "/../");
$dotenv->load();
function env($name, $default_value = null)
{
    $result = getenv($name);
    if($result === false) {
        return $default_value;
    } else {
        return $result;
    }
}

//
// Config tool
//
$config_reader = new Config(__DIR__ . "/../config/");
function config($config_key, $default_value = null)
{
    global $config_reader;
    
    return $config_reader->get($config_key, $default_value);
}

//
// File storage helper
//
function storage_path($relative_path = '')
{
    $storage_path = config('storage_path');
    
    return $storage_path . ltrim($relative_path, '/');
}

//
// Init dep container
//
$container = DI\ContainerBuilder::buildDevContainer();
function container()
{
    global $container;
    
    return $container;
}
