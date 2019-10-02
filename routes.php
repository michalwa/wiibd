<?php

/**
 * This script is called by `index.php` and is meant to configure
 * the `Router` (`$router`) by registering appropriate `Routes`.
 */

use Http\Request;
use Http\Response;
use View\View;

$router->setFallback(function($app, $request) {
    return View::load($app, 'errors/404')->toResponse($app, ['url' => $request.''], 404);
});
