<?php
session_start();
require "./vendor/autoload.php";

use App\Controllers\AppController;
use App\Controllers\ItemController;
use App\Controllers\Admin\AdminCategoriesController;
use App\Controllers\Admin\AdminItemsController;
use Maxbond\Mueble\App;

/**
 * Load and merge all configs
 */
$viewsConfig = include('./app/config/view.php');
$config = array_merge(include './app/config/database.php', $viewsConfig);

/**
 * Create core class
 */
try {
    $app = new App($config);
} catch (\Throwable $exception) {
    die($exception->getMessage());
}

/**
 * Define application routes
 */
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($app) {

    // Items routes
    $r->addRoute('GET', '/', AppController::class.'@index');
    $r->addRoute('GET', '/item/{id:\d+}', ItemController::class.'@item');
    $r->addRoute('GET', '/item/{id:\d+}/remove', ItemController::class.'@remove');
    $r->addRoute('GET', '/basket', ItemController::class.'@basket');
    $r->addRoute('POST', '/done', ItemController::class.'@done');

    // Categories list
    $r->addRoute('GET', '/categories/{id:\d+}', AppController::class.'@category');

    //Admin routes, CRUD
    $r->addRoute('GET', '/admin/categories', AdminCategoriesController::class.'@index');
    $r->addRoute('GET', '/admin/categories/create', AdminCategoriesController::class.'@create');
    $r->addRoute('GET', '/admin/categories/{id:\d+}/edit', AdminCategoriesController::class.'@edit');
    $r->addRoute('GET', '/admin/categories/{id:\d+}/delete', AdminCategoriesController::class.'@delete');
    $r->addRoute('POST', '/admin/categories/{id:\d+}', AdminCategoriesController::class.'@update');
    $r->addRoute('POST', '/admin/categories', AdminCategoriesController::class.'@store');

    $r->addRoute('GET', '/admin/items', AdminItemsController::class.'@index');
    $r->addRoute('GET', '/admin/items/create', AdminItemsController::class.'@create');
    $r->addRoute('GET', '/admin/items/{id:\d+}/edit', AdminItemsController::class.'@edit');
    $r->addRoute('GET', '/admin/items/{id:\d+}/delete', AdminItemsController::class.'@delete');
    $r->addRoute('POST', '/admin/items/{id:\d+}', AdminItemsController::class.'@update');
    $r->addRoute('POST', '/admin/items', AdminItemsController::class.'@store');
});

/**
 * Dispatch routes
 */
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
        //Special cases
    case FastRoute\Dispatcher::NOT_FOUND:
        echo $app->template->view('errors/404.twig', []);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo $app->template->view('errors/405.twig', []);
        break;
        // Call Controller with defined method
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $app->callController($handler,$vars);
        break;
}