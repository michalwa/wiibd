<?php

namespace App\Controllers;

use App\Entities\Author;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class AuthorController extends Controller {

    /**
     * @Route('GET', '/authors')
     */
    public function authorIndex(Request $request, $params): ?Response {
        if($search = $request->getQuery('search')) {
            $authors = Author::textSearch($search);
        } else {
            $authors = Author::getRepository()->all();
        }

        return View::load('author/index')->toResponse([
            'authors' => $authors,
            'search' => $search,
        ]);
    }

}
