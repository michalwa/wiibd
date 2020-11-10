<?php

namespace App\Controllers;

use App\Auth\UserSession;
use App\Entities\Item;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class ItemController extends Controller {

    /**
     * @Route('GET', '/items')
     */
    public function itemIndex(Request $request, $params): ?Response {
        if(!UserSession::isAdmin()) {
            return View::load('errors/401')->toResponse([
                'url' => $request->getPath(),
            ]);
        }

        if($search = $request->getQuery('search')) {
            $items = Item::textSearch($search);
        } else {
            $items = Item::getRepository()->all();
        }

        return View::load('item/index')->toResponse([
            'items' => $items,
            'search' => $search,
        ]);
    }

}
