<?php

require_once 'vendor/autoload.php';
require_once 'core/router.php';
require_once'routes.php';
require_once 'core/filter.php';
require_once 'config.php';
require_once 'core/carbineexception.php';
require_once 'core/input.php';
require_once 'core/controller.php';
foreach (glob(CONTROLLERS . "*.php") as $filename) {
  require_once($filename);
}


$url = trim($_GET['url']);
$router = new Routes();
$router->build();
$router->postInit();
$router->match($url);
