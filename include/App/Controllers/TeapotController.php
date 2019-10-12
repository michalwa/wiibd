<?php

namespace App\Controllers;

use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

/**
 * Controls the teapot
 */
class TeapotController extends Controller {

    /**
     * @Route('GET', '/brew/coffee')
     */
    public function brewCoffee(Request $request, $params): Response {
        return View::load('teapot')->toResponse([], 418);
    }

}
