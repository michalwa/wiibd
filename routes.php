<?php

/*
 * This script is called by `index.php` and is meant to configure
 * the `Router` by registering appropriate `Routes`.
 */

use Http\Request;
use Http\Response;
use View\View;

$router->setFallback(function($request) {
    return View::load('errors/404')->toResponse(['url' => $request.''], 404);
});
