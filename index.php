<?php
ini_set('display_errors', 1);
require "./vendor/autoload.php";

use App\Controllers\AppController;
use App\Controllers\ItemController;
use Maxbond\Mueble\App;

$viewsConfig = include('./app/config/view.php');
$config = array_merge(include './app/config/database.php', $viewsConfig);

try {
    $app = new App($config);
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($app) {
    $r->addRoute('GET', '/', AppController::class.'@index');
    $r->addRoute('GET', '/item/{id:\d+}', ItemController::class.'@item');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo $app->template->view('errors/404.twig', []);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo $app->template->view('errors/405.twig', []);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $app->callController($handler,$vars);
        break;
}