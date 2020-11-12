<?php

namespace App\Controllers;

use App;
use App\Auth\UserSession;
use App\Entities\Author;
use App\Entities\Book;
use App\Entities\Borrow;
use App\Entities\Genre;
use App\Entities\Publisher;
use Content\Form\Form;
use Content\Form\NumberField;
use Content\Form\SelectField;
use Content\Form\TextField;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class BookController extends Controller {

    public const SUCCESS = 'SUCCESS';

    /**
     * @var Form
     */
    private $newBookForm;

    public function __construct() {
        parent::__construct();

        $authorOptions = [];
        /** @var Author $author */
        foreach(Author::getRepository()->all() as $author) {
            $authorOptions[$author->getId()] = "$author->lastName $author->firstName";
        }

        $genreOptions = [];
        /** @var Genre $genre */
        foreach(Genre::getRepository()->all() as $genre) {
            $genreOptions[$genre->getId()] = ''.$genre;
        }

        $publisherOptions = [];
        /** @var Publisher $publisher */
        foreach(Publisher::getRepository()->all() as $publisher) {
            $publisherOptions[$publisher->getId()] = ''.$publisher;
        }

        $this->newBookForm = (new Form('POST', self::routeUrl('createBook')))
            ->addField(new TextField('title', true, ['label' => 'Tytuł']))
            ->addField(new SelectField('authors', $authorOptions, true, true, ['label' => 'Autor']))
            ->addField(new NumberField('releaseYear', true, ['min' => 1, 'label' => 'Rok wydania']))
            ->addField(new SelectField('publisher', $publisherOptions, true, false, ['label' => 'Wydawnictwo']))
            ->addField(new SelectField('genres', $genreOptions, false, true, ['label' => 'Gatunki']));
    }

    /**
     * @Route('GET', '/books')
     */
    public function bookIndex(Request $request, $params): ?Response {
        if($search = $request->getQuery('search')) {
            $books = Book::textSearch($search);
        } else {
            $books = Book::getRepository()->all();
        }

        return View::load('book/index')->toResponse([
            'books' => $books,
            'search' => $search,
            'filter' => $request->getQuery('filter'),
        ]);
    }

    /**
     * @Route('GET', '/books/{id:uint}')
     */
    public function bookDetail(Request $request, $params): ?Response {
        $book = Book::getRepository()->findById($params['id']);
        if($book === null) return null;

        $ctx = ['book' => $book];

        if(UserSession::isAdmin())
            $ctx['borrows'] = Borrow::findByBookId($book->getId());

        return View::load('book/book')->toResponse($ctx);
    }

    /**
     * @Route('GET', '/books/new')
     */
    public function newBookForm(Request $request, $param): ?Response {
        if(!UserSession::isAdmin())
            return Response::redirect(IndexController::routeUrl('index'));

        return View::load('book/new')->toResponse([
            'form' => $this->newBookForm,
        ]);
    }

    /**
     * @Route('POST', '/books/new')
     */
    public function createBook(Request $request, $param): ?Response {
        if(!UserSession::isAdmin())
            return Response::redirect(IndexController::routeUrl('index'));

        $form = $this->newBookForm;
        if(!$this->newBookForm->isValid())
            return $this->redirectToSelf('newBookForm');

        $book = new Book();
        $book->title = $form->getValue('title');
        $book->releaseYear = $form->getValue('releaseYear');

        $book->authors = Author::getRepository()
            ->findAllById($form->getValue('authors'))
            ->toArray();

        $book->genres = Genre::getRepository()
            ->findAllByid($form->getValue('genres'))
            ->toArray();

        $book->publisher = Publisher::getRepository()
            ->findById($form->getValue('publisher'));

        $book->persist();

        return View::load('book/new')->toResponse([
            'form' => $this->newBookForm,
            'info' => self::SUCCESS,
        ]);
    }

}
