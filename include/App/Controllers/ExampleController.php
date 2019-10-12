<?php

namespace App\Controllers;

use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;
use App\Entities\Dummy;

class ExampleController extends Controller {

    /**
     * @Route('GET', '/')
     */
    public function index(Request $request, $params): Response {
        return View::load('example')->toResponse([ 'request' => $request ]);
    }

    /**
     * @Route('GET', '/dummy/{id:uint}')
     */
    public function getDummy(Request $request, $params): Response {
        $repository = Dummy::getRepository();
        $dummy = $repository->findById($params['id']);
        if($dummy !== null) {
            return Response::text($dummy->name);
        }
        
        return $this->redirectToSelf('index');
    }

}
