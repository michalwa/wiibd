<?php
use App\Entities\Book;

$appName = App::get()->getConfig('app.name');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName ?> | Książki</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?= $this->include('include/navbar') ?>
    <div class="container">
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
                <td><?= $book->title ?></td>
                <td><?= implode(', ', $book->authors) ?></td>
                <td><?= $book->publisher ?></td>
                <td><?= $book->releaseYear ?></td>
                <td><?= implode(', ', $book->genres) ?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
