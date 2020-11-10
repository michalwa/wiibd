<!-- extends base -->

<?php
use App\Controllers\AuthorController;
use App\Controllers\UserController;
use App\Entities\Borrow;

/** @var App\Entities\Book */
$book = $params['book'];
?>

<!-- begin head -->
<title><?= App::getName() ?> | <?= $book->title ?></title>
<link rel="stylesheet" href="<?= App::getPublicUrl('css/product-details.css') ?>">
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col-md-auto mb-3">
                    <img class="img-responsive"
                        id="book-cover"
                        src="https://picsum.photos/200/300"
                        alt="Okładka">
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col">
                            <h2><?= $book->title ?></h2>
                            <ul class="product-details">
                                <li>
                                    <i class="fa fa-user" title="Autorzy"></i>
                                <?php $first = true; foreach($book->authors as $author): ?>
                                <?php if(!$first): ?>&bullet;<?php endif; ?>
                                    <a href="<?=
                                        App::routeUrl(
                                            AuthorController::class,
                                            'authorDetail',
                                            ['id' => $author->getId()])
                                    ?>"><?= $author ?></a>
                                <?php $first = false; endforeach; ?>
                                </li>
                                <li>
                                    <i class="fa fa-building" title="Wydawnictwo"></i>
                                    <?= $book->publisher ?>
                                </li>
                                <li>
                                    <i class="fa fa-calendar" title="Rok wydania"></i>
                                    <?= $book->releaseYear ?>
                                </li>
                                <li>
                                    <i class="fa fa-tag" title="Gatunki"></i>
                                    <?= implode(', ', $book->genres) ?>
                                </li>
                                <li>
                                    <i class="fa fa-cubes"></i>
                                <?php if(($n = $book->numAvailableCopies()) === 0): ?>
                                    <span class="text-danger">Niedostępna</span>
                                <?php else: ?>
                                    <span class="text-success">Dostępne egzemplarze: <?= $n ?></span>
                                <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php if(isset($params['borrows'])): ?>
    <div class="row mt-4">
        <h2 class="mb-4">Wypożyczona przez</h2>

        <table class="table">
            <tr>
                <th>Login</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Data wypożyczenia</th>
                <th>Data oddania</th>
            </tr>
        <?php /** @var Borrow $borrow */ foreach($params['borrows'] as $borrow): ?>
        <?php $userUrl = App::routeUrl(
            UserController::class,
            'userDetail',
            ['id' => $borrow->user->getId()]);
        ?>
            <tr>
                <td><a href="<?= $userUrl ?>">
                    <?= $borrow->user->username ?></a></td>
                <td><a href="<?= $userUrl ?>">
                    <?= $borrow->user->firstName ?></a></td>
                <td><a href="<?= $userUrl ?>">
                    <?= $borrow->user->lastName ?></a></td>
                <td><time><?= $borrow->began ?></time></td>
                <td><time><?= $borrow->ends ?></time></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>
</div>
<!-- end -->
