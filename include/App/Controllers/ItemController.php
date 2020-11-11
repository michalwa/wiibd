<?php

namespace App\Controllers;

use App;
use App\Auth\UserSession;
use App\Entities\Book;
use App\Entities\Borrow;
use App\Entities\Item;
use App\Entities\User;
use Content\Form\DateField;
use Content\Form\Form;
use Content\Form\SelectField;
use Content\Form\TextField;
use Controller\Controller;
use Http\Request;
use Http\Response;
use LogicException;
use View\View;

class ItemController extends Controller {

    public const ERROR_IDENTIFIER_EXISTS = 'ERROR_IDENTIFIER_EXISTS';
    public const SUCCESS = 'SUCCESS';

    /**
     * @var Form
     */
    private $lendForm;

    /**
     * @var Form
     */
    private $newItemsForm;

    public function __construct() {
        parent::__construct();

        $itemOptions = [];
        /** @var Item $item */
        foreach(Item::getRepository()->all() as $item) {
            if($item->available())
                $itemOptions[$item->getId()] = "$item->identifier | {$item->book->title}";
        }

        $userOptions = [];
        /** @var User $user */
        foreach(User::getRepository()->all() as $user) {
            $userOptions[$user->getId()] = "$user->lastName $user->firstName, $user->class";
        }

        $this->lendForm = (new Form('POST', App::routeUrl(self::class, 'lendItem')))
            ->addField(new SelectField('item', $itemOptions, true, false, ['label' => 'Egzemplarz']))
            ->addField(new SelectField('user', $userOptions, true, false, ['label' => 'Użytkownik']))
            ->addField(new DateField('began', true, ['label' => 'Data wypożyczenia', 'value' => date('Y-m-d')]))
            ->addField(new DateField('ends', true, ['label' => 'Data zwrotu']));

        $bookOptions = [];
        /** @var Book $book */
        foreach(Book::getRepository()->all() as $book) {
            $bookOptions[$book->getId()] = $book->title.' ('.implode(', ', $book->authors).')';
        }

        $this->newItemsForm = (new Form('POST', App::routeUrl(self::class, 'newItems')))
            ->addField(new SelectField('book', $bookOptions, true, false, ['label' => 'Książka']))
            ->addField(new TextField('identifiers', true, [
                'label' => 'Numery inwentarzowe',
                'hint' => 'Oddziel kolejne numery spacjami']));
    }

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
     * @Route('GET', '/items/lend')
     */
    public function lendForm(Request $request, $params): ?Response {
        if(!UserSession::isAdmin())
            return $this->redirect(IndexController::class.'::index');

        return View::load('item/lend')->toResponse([
            'itemId' => (int)$request->getQuery('item'),
            'form' => $this->lendForm,
        ]);
    }

    /**
     * @Route('POST', '/items/lend')
     */
    public function lendItem(Request $request, $params): ?Response {
        if(!UserSession::isAdmin())
            return $this->redirect(IndexController::class.'::index');

        $form = $this->lendForm;

        if(!$form->isValid())
            return $this->redirectToSelf('lendForm', [], $request->getQuery());

        /** @var Item $item */
        $item = Item::getRepository()->findById($form->getValue('item'));

        if(!$item->available())
            throw new LogicException("Cannot lend unavailable item");

        $user = User::getRepository()->findById($form->getValue('user'));

        $borrow = new Borrow();
        $borrow->item = $item;
        $borrow->user = $user;
        $borrow->began = $form->getValue('began');
        $borrow->ends = $form->getValue('ends');
        $borrow->active = true;
        $borrow->persist();

        $borrow->user->active = true;
        $borrow->user->persist();

        return View::load('item/lend')->toResponse([
            'form' => $this->lendForm,
            'info' => self::SUCCESS,
        ]);
    }

    /**
     * @Route('GET', '/items/new')
     */
    public function newItemsForm(Request $request, $params): ?Response {
        if(!UserSession::isAdmin())
            return $this->redirect(IndexController::class.'::index');

        return View::load('item/new')->toResponse([
            'form' => $this->newItemsForm,
        ]);
    }

    /**
     * @Route('POST', '/items/new')
     */
    public function newItems(Request $request, $params): ?Response {
        if(!UserSession::isAdmin())
            return $this->redirect(IndexController::class.'::index');

        $form = $this->newItemsForm;

        if(!$form->isValid())
            return $this->redirectToSelf('newItemsForm');

        $book = Book::getRepository()->findById($form->getValue('book'));

        $items = [];
        foreach(explode(' ', $form->getValue('identifiers')) as $itemId) {
            if($itemId !== '') {
                if(Item::findByIdentifier($itemId) !== null) {
                    return View::load('item/new')->toResponse([
                        'form' => $this->newItemsForm,
                        'info' => self::ERROR_IDENTIFIER_EXISTS,
                    ]);
                }

                $item = new Item();
                $item->identifier = $itemId;
                $item->book = $book;
                $items[] = $item;
            }
        }

        foreach($items as $item) $item->persist();

        return View::load('item/new')->toResponse([
            'form' => $this->newItemsForm,
            'info' => self::SUCCESS,
        ]);
    }

}
