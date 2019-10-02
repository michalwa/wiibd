<?php

use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class ExampleController extends Controller {

    /**
     * Route: GET "/"
     */
    public function index(App $app, Request $request, $params): Response {
        return View::load($app, 'example')->toResponse($app, [ 'request' => $request ]);
    }

}
