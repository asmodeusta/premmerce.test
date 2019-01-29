<?php

$start_time = microtime(true);

defined('ROOT') or define('ROOT', dirname(__FILE__));
defined('APP') or define('APP', ROOT . '/app');

require_once APP . '/components/autoload.php';

$url = $_SERVER['REQUEST_URI'];
$router = new Router();
$router->run($url);

//echo '<pre>', microtime(true)-$start_time, '</pre>';