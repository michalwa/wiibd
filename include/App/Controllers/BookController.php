<?php

namespace App\Controllers;

use App\Entities\Author;
use App\Entities\Book;
use App\Entities\Genre;
use App\Entities\Publisher;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class BookController extends Controller {

    /**
     * @Route('GET', '/')
     */
    public function index(Request $request, $params): Response {
        $books = Book::getRepository()->all();
        return View::load('index')->toResponse([
            'books' => $books,
        ]);
    }

    /**
     * @Route('GET', '/test')
     */
    public function test(Request $request, $params): Response {
        $book = new Book();
        $book->title = 'Grube wióry';
        $book->releaseYear = 2020;

        $publisher = new Publisher();
        $publisher->name = 'Agora';
        $book->publisher = $publisher;

        $author = new Author();
        $author->firstName = 'Rafał';
        $author->lastName = 'Pacześ';
        $book->authors[] = $author;

        $genre = new Genre();
        $genre->label = 'satyra';
        $book->genres[] = $genre;

        $book->persist();

        return Response::text('OK');
    }

}
