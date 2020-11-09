<?php

namespace App\Controllers;

use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class IndexController extends Controller {

    /**
     * @Route('GET', '/')
     */
    public function index(Request $request, $params): ?Response {
        return View::load('index')->toResponse();
    }

}
