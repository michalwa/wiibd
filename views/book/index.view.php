<!-- extends base -->

<?php
use App\Controllers\BookController;
use App\Entities\Book;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Książki</title>
<!-- end -->

<!-- begin body -->
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
                        <th scope="col">Dostępność</th>
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
                            <td>
                            <?php if(($n = $book->numAvailableCopies()) === 0): ?>
                                <span class="text-danger">Niedostępna</span>
                            <?php else: ?>
                                <span class="text-success"><?= $n ?></span>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end -->
