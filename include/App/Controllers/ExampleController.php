<?php

namespace App\Controllers;

use \App;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;
use Database\Database;
use App\Entities\Dummy;

class ExampleController extends Controller {

    /**
     * @Route('GET', '/')
     */
    public function index(Request $request, $params): Response {
        Database::get()->connect();
        return View::load('example')->toResponse([
            'request' => $request,
            'db_ok'   => Database::get()->isConnected(),
            'db_name' => App::get()->getConfig('database.name'),
        ]);
    }

    /**
     * @Route('GET', '/dummies')
     */
    public function getDummies(Request $request, $params): Response {
        $repository = Dummy::getRepository();
        return View::load('dummies/index')->toResponse([
            'dummies' => $repository->all()
        ]);
    }

    /**
     * @Route('GET', '/dummies/{id:uint}')
     */
    public function getDummy(Request $request, $params): Response {
        $repository = Dummy::getRepository();
        /** @var \App\Entities\Dummy $dummy */
        $dummy = $repository->findById($params['id']);
        if($dummy !== null) {
            return Response::text($dummy->name);
        }
        
        return $this->redirectToSelf('index');
    }

}
