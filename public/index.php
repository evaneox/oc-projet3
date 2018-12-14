<?php
use App\Core\Router;

/* ==================================
  Définition des chemins d'accès
=========================================*/
define('ROOT',  dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP',   ROOT . 'app' . DIRECTORY_SEPARATOR);

/* ==================================
  Auto-chargement de composer
=========================================*/
require ROOT . 'vendor/autoload.php';

/* ==================================
  Chargement de la configuration
=========================================*/
require APP . 'Config/config.php';

/* ==================================
  Enregistrement des routes
=========================================*/
$router = new Router($_GET['url']);

require APP . 'Config/routes.php';

/* ==================================
  Execution des routes
=========================================*/
$router->run();



