<!-- extends base -->

<?php

use App\Auth\UserSession;
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
        <div class="col-lg-3 mb-4">
            <form action="#" method="get">
                <div class="input-group">
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
                <div class="form-check my-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="filter"
                        value="available"
                        id="availableFilterChkb"
                        <?= $params['filter'] === 'available' ? 'checked' : '' ?>>
                    <label
                        class="form-check-label"
                        for="availableFilterChkb">
                        Tylko dostępne
                    </label>
                </div>
                <div class="form-group">
                    <label for="genresSelect">Gatunki</label>
                    <select class="form-control" name="genres[]" id="genresSelect" multiple>
                    <?php foreach($params['genres'] as $genre): ?>
                        <option value="<?= $genre->getid() ?>"
                            <?= in_array($genre->getId(), $params['selectedGenres'] ?? []) ? 'selected' : '' ?>>
                            <?= $genre ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">
                    <i class="fa fa-search"></i>&nbsp;
                    Szukaj
                </button>
            </form>

        <?php if(UserSession::isAdmin()): ?>
            <a href="<?= BookController::routeUrl('newBookForm') ?>"
                class="btn btn-light w-100 mt-4">
                <i class="fa fa-plus"></i>&nbsp;
                Dodaj książkę
            </a>
        <?php endif; ?>
        </div>
        <div class="col-lg-9">
            <div class="table-responsive-lg">
                <table class="table " id="books">
                    <tr>
                        <th>Tytuł</th>
                        <th>Autor</th>
                        <th>Wydawnictwo</th>
                        <th>Gatunek</th>
                        <th>Dostępność</th>
                    </tr>
                    <?php /** @var Book $book */ foreach($params['books'] as $book): ?>
                    <?php if($params['filter'] !== 'available' || $book->numAvailableCopies() > 0): ?>
                        <tr>
                            <td>
                                <a href="<?= BookController::routeUrl('bookDetail', ['id' => $book->getId()]) ?>">
                                    <?= $book->title ?>
                                </a>
                            </td>
                            <td><?= implode(', ', $book->authors) ?></td>
                            <td><?= $book->publisher ?></td>
                            <td><?= implode(', ', $book->genres) ?></td>
                            <td>
                            <?php if(($n = $book->numAvailableCopies()) === 0): ?>
                                <span class="text-danger">Niedostępna</span>
                            <?php else: ?>
                                <span class="text-success"><?= $n ?></span>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end -->
