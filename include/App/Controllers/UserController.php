<?php

namespace App\Controllers;

use App\Entities\Book;
use App\Entities\Borrow;
use App\Entities\User;
use Controller\Controller;
use Database\Database;
use Database\Query\QueryParams;
use Database\Query\Select;
use Http\Request;
use Http\Response;
use Utils\Session;
use View\View;

class UserController extends Controller {

    /**
     * @Route('GET', '/users')
     */
    public function userIndex(Request $request, $params): Response {

        // TODO: Move permission checking into a dedicated utility
        if(Session::get('admin') === null) {
            return View::load('errors/401')->toResponse([
                'url' => $request->getPath(),
            ]);
        }

        if($search = $request->getQuery('search')) {
            $users = User::textSearch($search);
        } else {
            $users = User::getRepository()->all();
        }

        return View::load('user/index')->toResponse([
            'users' => $users,
            'search' => $search,
        ]);
    }

    /**
     * @Route('GET', '/users/{id:uint}')
     */
    public function userDetail(Request $request, $params): Response {

        // TODO: Move permission checking into a dedicated utility
        if(Session::get('admin') === null) {
            if(($id = Session::get('user')) === null || $id != $params['id']) {
                return View::load('errors/401')->toResponse([
                    'url' => $request->getPath(),
                ]);
            }
        }

        $user = User::getRepository()->findById($params['id']);

        if($user === null) {
            return View::load('errors/404')->toResponse([
                'url' => $request->getPath()
            ]);
        }

        $borrows = Borrow::getRepository()->query(fn(QueryParams $params) => <<<SQL
            SELECT wypozyczenia.*
            FROM ksiazki
            INNER JOIN egzemplarze ON ksiazki.id = egzemplarze.ksiazka
            INNER JOIN wypozyczenia ON wypozyczenia.egzemplarz = egzemplarze.id
            WHERE wypozyczenia.czytelnik = {$params->add($user->getId())}
        SQL);

        return View::load('user/user')->toResponse([
            'user' => $user,
            'borrows' => $borrows,
        ]);
    }

}
