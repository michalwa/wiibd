<?php

namespace App\Controllers;

use App\Entities\Book;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class BookController extends Controller {

    /**
     * @Route('GET', '/books')
     */
    public function book_index(Request $request, $params): Response {
        $books = Book::getRepository()->all();
        return View::load('book/index')->toResponse([
            'books' => $books,
        ]);
    }

    /**
     * @Route('GET', '/books/{id:uint}')
     */
    public function book_detail(Request $request, $params): Response {
        $book = Book::getRepository()->findById($params['id']);

        if($book === null) {
            return View::load('errors/404')->toResponse([
                'url' => $request->getPath()
            ]);
        }

        return View::load('book/book')->toResponse([
            'book' => $book,
        ]);
    }

}
