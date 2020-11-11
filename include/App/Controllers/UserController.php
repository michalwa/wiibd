<?php

namespace App\Controllers;

use App\Auth\UserSession;
use App\Entities\Borrow;
use App\Entities\User;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class UserController extends Controller {

    /**
     * @Route('GET', '/users')
     */
    public function userIndex(Request $request, $params): ?Response {
        if(!UserSession::isAdmin()) {
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
    public function userDetail(Request $request, $params): ?Response {
        $user = User::getRepository()->findById($params['id']);
        if($user === null) return null;

        if(!UserSession::isAdmin()) {
            if(UserSession::isUser($params['id'])) {
                return $this->redirectToSelf('selfUserDetail');
            }

            return View::load('errors/401')->toResponse([
                'url' => $request->getPath(),
            ]);
        }

        $borrow = Borrow::findActiveByUserId($user->getId());

        return View::load('user/user')->toResponse([
            'user' => $user,
            'borrows' => $borrow,
        ]);
    }

    /**
     * @Route('GET', '/me')
     */
    public function selfUserDetail(Request $request, $params): ?Response {
        if(($user = UserSession::getUser()) === null) {
            return $this->redirect(IndexController::class.'::index');
        }

        $borrows = Borrow::findActiveByUserId($user->getId());

        return View::load('user/user')->toResponse([
            'self' => true,
            'user' => $user,
            'borrows' => $borrows,
        ]);
    }

    /**
     * @Route('GET', '/users/{id:uint}/delete')
     */
    public function deleteUser(Request $request, $params): ?Response {
        if(UserSession::isAdmin()) {
            $user = User::getRepository()->findById($params['id']);

            $borrows = Borrow::findActiveByUserId($user->getId())->toArray();
            if(count($borrows) === 0) $user->delete();
        }

        return $this->redirect(IndexController::class.'::index');
    }

}
