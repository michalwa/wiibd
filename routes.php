<?php

/**
 * This script is called by `index.php` and is meant to configure
 * the `Router` (`$router`) by registering appropriate `Routes`.
 */

use Routing\Router;
use Routing\Routes\PatternRoute;
use Http\Request;
use Http\Response;
use View\View;

$router->setFallback(function($app, $request) {
    return View::load($app, 'errors/404')->toResponse($app, ['url' => $request.''], 404);
});

$router->add(PatternRoute::new('/', function($app, $req, $params) {
    return View::load($app, 'example')->toResponse($app);
}));
