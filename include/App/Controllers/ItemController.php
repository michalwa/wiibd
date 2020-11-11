<?php

namespace App\Controllers;

use App\Auth\UserSession;
use App\Entities\Borrow;
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

    /**
     * @Route('GET', '/items/{id:uint}/return')
     */
    public function returnItem(Request $request, $params): ?Response {
        /** @var Item $item */
        $item = Item::getRepository()->findById($params['id']);

        dump($item);

        if(UserSession::isAdmin()) {
            /** @var Borrow $borrow */
            $borrow = Borrow::findActiveByItemId($item->getId());
            $borrow->active = false;
            $borrow->persist();
        }

        return $this->redirectToSelf('itemIndex', [], [], 'item-'.$item->identifier);
    }

    /**
     * @Route('GET', '/items/{id:uint}/lend')
     */
    public function lendItem(Request $request, $params): ?Response {
        /** @var Item $item */
        $item = Item::getRepository()->findById($params['id']);

        if(UserSession::isAdmin()) {
            // TODO: Lend item
        }

        return $this->redirectToSelf('itemIndex', [], [], 'item-'.$item->identifier);
    }

}
