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

// Read config, nitialize the app
$config = new Config(new Files\Path(__DIR__, 'config.ini'));
$app = App::init(__DIR__, $config);

// Configure the router
$router = $app->getRouter();
$router->add(new Routing\Routes\PublicResourceRoute());
include 'routes.php';

// Initialize controllers
$controllersDir = $app->getConfig('controllers.dir');
$controllerFiles = array_diff(scandir($controllersDir), ['.', '..']);
foreach($controllerFiles as $file) {
    require (new Files\Path($controllersDir, $file));
    $className = explode('.', $file)[0];
    new $className();
}

// Handle the request
$request = Http\Request::get();
if(($response = $router->handle($request)) !== null) {
    $response->send();
} else {
    Http\Response::text("No matching route found for the URL: ".$request, 404)->send();
}
