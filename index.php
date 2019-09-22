<?php

/*
 * This script handles all incoming HTTP requests.
 * Serve with Apache or using `php -S localhost:80 index.php`.
 */

// Register an autoloader
spl_autoload_register(function ($class) {
    $file = rtrim(__DIR__, '/\\').'/include/'.str_replace('\\', '/', $class).'.php';
    if(file_exists($file)) {
        include $file;
    }
});

// Initialize the app with the server-side root directory and client-side root URL
$app = new App(__DIR__, '');

// Configure the router
$router = new Routing\Router();
$router->add(new Routing\Routes\PublicResourceRoute());
include 'routes.php';

// Handle the request
$request = Http\Request::get($app);
if(($response = $router->handle($app, $request)) !== null) {
    $response->send();
} else {
    // 404
    $response = Http\Response::text("No matching route found for the URL ".$request);
    $response->setStatus(404);
    $response->send();
}
