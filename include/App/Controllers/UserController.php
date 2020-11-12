<?php

namespace App\Controllers;

use App;
use App\Auth\UserSession;
use App\Entities\Borrow;
use App\Entities\User;
use App\PasswordValidator;
use Content\Form\Form;
use Content\Form\PasswordField;
use Content\Form\SelectField;
use Content\Form\TextField;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class UserController extends Controller {

    public const SUCCESS = 'SUCCESS';
    public const ERROR_USERNAME_EXISTS = 'ERROR_USERNAME_EXISTS';
    public const ERROR_PASSWORD_TOO_WEAK = 'ERROR_PASSWORD_TOO_WEAK';

    /**
     * @var Form
     */
    private $newUserForm;

    public function __construct() {
        parent::__construct();

        $this->newUserForm = (new Form('POST', self::routeUrl('newUser')))
            ->addField(new TextField('username', true, ['label' => 'Login']))
            ->addField(new PasswordField('password', true, ['label' => 'Hasło']))
            ->addField(new TextField('firstName', true, ['label' => 'Imię']))
            ->addField(new TextField('lastName', true, ['label' => 'Nazwisko']))
            ->addField(new TextField('class', true, ['label' => 'Klasa']));
    }

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

        $classNames = iterator_to_array(User::allClasses());

        return View::load('user/index')->toResponse([
            'users' => $users,
            'search' => $search,
            'classNames' => $classNames,
            'class' => $request->getQuery('class') ?: null,
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

        $borrows = Borrow::findByUserId($user->getId());
        $canDelete = true;
        foreach($borrows as $borrow) {
            if($borrow->active) {
                $canDelete = false;
                break;
            }
        }

        return View::load('user/user')->toResponse([
            'user' => $user,
            'borrows' => $borrows,
            'canDelete' => $canDelete,
        ]);
    }

    /**
     * @Route('GET', '/me')
     */
    public function selfUserDetail(Request $request, $params): ?Response {
        if(($user = UserSession::getUser()) === null)
            return Response::redirect(IndexController::routeUrl('index'));

        $borrows = Borrow::findByUserId($user->getId())->toArray();

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

            $borrows = Borrow::findByUserId($user->getId())->toArray();
            foreach($borrows as $borrow) {
                if($borrow->active)
                    return Response::redirect(IndexController::routeUrl('index'));

                $borrow->delete();
            }

            $user->delete();
        }

        return Response::redirect(IndexController::routeUrl('index'));
    }

    /**
     * @Route('GET', '/users/new')
     */
    public function newUserForm(Request $request, $params): ?Response {
        if(!UserSession::isAdmin())
            return $this->redirectToSelf('userIndex');

        return View::load('user/new')->toResponse([
            'form' => $this->newUserForm,
        ]);
    }

    /**
     * @Route('POST', '/users/new')
     */
    public function newUser(Request $request, $params): ?Response {
        if(!UserSession::isAdmin())
            return $this->redirectToSelf('userIndex');

        $form = $this->newUserForm;

        if(!$form->isValid())
            return $this->redirectToSelf('newUserForm');

        $username = $form->getValue('username');
        $password = $form->getValue('password');

        if(User::findByUsername($username) !== null) {
            return View::load('user/new')->toResponse([
                'form' => $this->newUserForm,
                'info' => self::ERROR_USERNAME_EXISTS,
            ]);
        }

        if(!PasswordValidator::isValidPassword($password)) {
            return View::load('user/new')->toResponse([
                'form' => $this->newUserForm,
                'info' => self::ERROR_PASSWORD_TOO_WEAK,
            ]);
        }

        $user = new User();
        $user->username = $username;
        $user->setPassword($password);
        $user->firstName = $form->getValue('firstName');
        $user->lastName = $form->getValue('lastName');
        $user->class = $form->getValue('class');
        $user->active = false;
        $user->persist();

        return View::load('user/new')->toResponse([
            'form' => $this->newUserForm,
            'info' => self::SUCCESS,
        ]);
    }

}
