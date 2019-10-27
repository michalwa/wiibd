<?php

/*
 * This script handles all incoming HTTP requests.
 * Serve with Apache or using `php -S localhost:80 index.php`.
 */

use Files\Files;
use Files\Path;
use Routing\Route\PublicResourceRoute;
use Http\Request;
use Http\Response;
use Database\Database;
use View\View;

require 'common.php';

define('INCLUDE_DIR', rtrim(__DIR__, '/\\').'/include/');

// Register an autoloader
spl_autoload_register(function(string $class) {
    $file = INCLUDE_DIR.str_replace('\\', '/', $class).'.php';
    if(file_exists($file)) {
        include $file;
    }
});

// Read config, nitialize the app
$config = new Config(new Path(__DIR__, 'config.ini').'');
$app = App::init(__DIR__, $config);

// Set error handler
set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline) {
    View::load('errors/500')->toResponse([
        'class'   => null,
        'code'    => $errno,
        'file'    => $errfile,
        'line'    => $errline,
        'message' => $errstr,
        'trace'   => [],
    ], 500)->send();
    die();
});

// Set exception handler
set_exception_handler(function(Throwable $e) {
    View::load('errors/500')->toResponse([
        'class'   => get_class($e),
        'code'    => $e->getCode(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'message' => $e->getMessage(),
        'trace'   => $e->getTrace(),
    ], 500)->send();
    die();
});

// Initialize database
Database::init($config);
Files::requireAll(INCLUDE_DIR.$app->getConfig('database.entitiesDir'));

// Configure the router
$router = $app->getRouter();
$router->add(new PublicResourceRoute());
include 'routes.php';

// Initialize controllers
$controllers = Files::requireAll(INCLUDE_DIR.$app->getConfig('controllers.dir'), true);

// Handle the request
$request = Request::get();
if(($response = $router->handle($request)) !== null) {
    $response->send();
} else {
    Response::text("No matching route found for the URL: ".$request, 'utf-8', 404)->send();
}
