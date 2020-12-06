<?php

namespace App\Controllers;

use App\UserSession;
use Content\Form\Form;
use Content\Form\PasswordField;
use Content\Form\TextField;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class LoginController extends Controller {

    /**
     * @var Form
     */
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = (new Form('POST', self::routeUrl('loginSubmit')))
            ->addField(new TextField('username', true, ['label' => 'Login']))
            ->addField(new PasswordField('password', true, ['label' => 'Hasło']));
    }

    /**
     * @Route('GET', '/login')
     */
    public function loginForm(Request $request, array $params): Response {
        return View::load('login')->toResponse([
            'form' => $this->form,
        ]);
    }

    /**
     * @Route('POST', '/login')
     */
    public function loginSubmit(Request $request, array $params): Response {
        if(!$this->form->isValid($request))
            return $this->redirectToSelf('loginForm');

        $username = $this->form->getValue('username');
        $password = $this->form->getValue('password');

        if(($error = UserSession::login($username, $password))) {
            return View::load('login')->toResponse([
                'form' => $this->form,
                'error' => $error,
            ]);
        }

        return Response::redirect(IndexController::routeUrl('index'));
    }

    /**
     * @Route('GET', '/logout')
     */
    public function logout(Request $request, array $params): Response {
        UserSession::logout();
        return Response::redirect(IndexController::routeUrl('index'));
    }

}
