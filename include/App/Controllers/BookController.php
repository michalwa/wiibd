<?php

namespace App\Controllers;

use App\Auth\UserSession;
use App\Entities\Book;
use App\Entities\Borrow;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class BookController extends Controller {

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

}
