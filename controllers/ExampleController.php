<?php

use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class ExampleController extends Controller {

    /**
     * @Routing\Annotations\Route('GET', '/')
     */
    public function index(Request $request, $params): Response {
        return View::load('example')->toResponse([ 'request' => $request ]);
    }

}