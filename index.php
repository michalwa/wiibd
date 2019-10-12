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

// Register an autoloader
spl_autoload_register(function ($class) {
    $file = rtrim(__DIR__, '/\\').'/include/'.str_replace('\\', '/', $class).'.php';
    if(file_exists($file)) {
        include $file;
    }
});

// Read config, nitialize the app
$config = new Config(new Path(__DIR__, 'config.ini').'');
$app = App::init(__DIR__, $config);

// Initialize database
Database::init($config);
Files::requireAll($app->getConfig('database.entitiesDir'));

// Configure the router
$router = $app->getRouter();
$router->add(new PublicResourceRoute());
include 'routes.php';

// Initialize controllers
Files::requireAll($app->getConfig('controllers.dir'), true);

// Handle the request
$request = Request::get();
if(($response = $router->handle($request)) !== null) {
    $response->send();
} else {
    Response::text("No matching route found for the URL: ".$request, 404)->send();
}
