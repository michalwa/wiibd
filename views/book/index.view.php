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
        <h1 class="mb-4">Książki</h1>
        <div class="row">
            <div class="col-md-3 mb-4">
                <form action="#" method="get">
                    <div class="input-group mb-2">
                        <input
                            class="form-control"
                            id="searchInput"
                            type="search"
                            name="search"
                            placeholder="Szukaj"
                            value="<?= htmlescape($params['search'] ?? '') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-search"></i>&nbsp;
                            Szukaj
                        </button>
                    </div>
                </form>
            </div>
            <div class="col md-9">
                <div class="table-responsive">
                    <table class="table " id="books">
                        <tr>
                            <th scope="col">Tytuł</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Wydawnictwo</th>
                            <!-- <th scope="col">Rok wydania</th> -->
                            <!-- <th scope="col">Gatunek</th> -->
                        </tr>
                        <?php /** @var Book $book */ foreach($params['books'] as $book): ?>
                            <tr>
                                <td><a href="<?= App::routeUrl(
                                    BookController::class,
                                    'bookDetail',
                                    ['id' => $book->getId()]) ?>">
                                    <?= $book->title ?></a></td>

                                <td><?= implode(', ', $book->authors) ?></td>
                                <td><?= $book->publisher ?></td>
                                <!-- <td><?= $book->releaseYear ?></td> -->
                                <!-- <td><?= implode(', ', $book->genres) ?></td> -->
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('include/scripts') ?>
</body>
</html>
