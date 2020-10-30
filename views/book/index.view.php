<?php
use App\Controllers\BookController;
use App\Entities\Book;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App::getName() ?> | Książki</title>

    <?= $this->include('include/styles') ?>
</head>
<body>
    <?= $this->include('include/navbar') ?>
    <div class="container">
        <h1>Książki</h1>

        <p>
            <table class="table " id="books">
                <tr>
                    <th scope="col">Tytuł</th>
                    <th scope="col">Autor</th>
                    <th scope="col">Wydawnictwo</th>
                    <th scope="col">Rok wydania</th>
                    <th scope="col">Gatunek</th>
                </tr>
            <?php /** @var Book $book */ foreach($params['books'] as $book): ?>
                <tr>
                    <td><a href="<?= App::routeUrl(
                        BookController::class,
                        'book_detail',
                        ['id' => $book->getId()]) ?>">
                        <?= $book->title ?></a></td>

                    <td><?= implode(', ', $book->authors) ?></td>
                    <td><?= $book->publisher ?></td>
                    <td><?= $book->releaseYear ?></td>
                    <td><?= implode(', ', $book->genres) ?></td>
                </tr>
            <?php endforeach; ?>
            </table>
        </p>
    </div>

    <?= $this->include('include/scripts') ?>
</body>
</html>
