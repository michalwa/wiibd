<?php

namespace App\Controllers;

use App;
use App\Auth\UserSession;
use App\PasswordValidator;
use Content\Form\Form;
use Content\Form\PasswordField;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class PasswordChangeController extends Controller {

    public const ERROR_PASSWORDS_DONT_MATCH = 'ERROR_PASSWORDS_DONT_MATCH';
    public const ERROR_INVALID_PASSWORD = 'ERROR_INVALID_PASSWORD';
    public const ERROR_PASSWORD_TOO_WEAK = 'ERROR_PASSWORD_TOO_WEAK';
    public const SUCCESS = 'SUCCESS';

    /**
     * @var Form
     */
    private $form;

    public function __construct() {
        parent::__construct();

        $this->form = (new Form('POST', self::routeUrl('change')))
            ->addField(new PasswordField('old', true, ['label' => 'Obecne hasło']))
            ->addField(new PasswordField('new', true, ['label' => 'Nowe hasło']))
            ->addField(new PasswordField('confirm', true, ['label' => 'Powtórz nowe hasło']));
    }

    /**
     * @Route('GET', '/me/password')
     */
    public function form(Request $request, $param): ?Response {
        if(!UserSession::isUser())
            return Response::redirect(IndexController::routeUrl('index'));

        return View::load('user/password-form')->toResponse([
            'form' => $this->form,
        ]);
    }

    /**
     * @Route('POST', '/me/password')
     */
    public function change(Request $request, $param): ?Response {
        if(($user = UserSession::getUser()) === null)
            return Response::redirect(IndexController::routeUrl('index'));

        $old = $this->form->getValue('old');
        $new = $this->form->getValue('new');
        $confirm = $this->form->getValue('confirm');

        if(!$this->form->isValid())
            return $this->redirectToSelf('form');

        if(!$user->passwordEquals($old)) {
            return View::load('user/password-form')->toResponse([
                'form' => $this->form,
                'info' => self::ERROR_INVALID_PASSWORD,
            ]);
        }

        if($new !== $confirm) {
            return View::load('user/password-form')->toResponse([
                'form' => $this->form,
                'info' => self::ERROR_PASSWORDS_DONT_MATCH,
            ]);
        }

        if(!PasswordValidator::isValidPassword($new)) {
            return View::load('user/password-form')->toResponse([
                'form' => $this->form,
                'info' => self::ERROR_PASSWORD_TOO_WEAK,
            ]);
        }

        $user->setPassword($new);
        $user->persist();

        return View::load('user/password-form')->toResponse([
            'form' => $this->form,
            'info' => self::SUCCESS,
        ]);
    }

}
