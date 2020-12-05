<!-- extends base -->

<?php
use App\Controllers\BookController;
?>

<!-- begin head -->
<title><?= App::getName() ?> | <?= $params['author'] ?></title>
<link rel="stylesheet" href="<?= App::getPublicUrl('css/product-details.css') ?>">
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="row mb-4">
        <div class="col-md">
            <div class="row">
                <div class="col-md-auto mb-3">
                    <img class="img-responsive"
                        id="book-cover"
                        src="https://picsum.photos/200/200"
                        alt="Awatar">
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col">
                            <h2><?= $params['author'] ?></h2>
                            <ul class="product-details"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <h2 class="mb-4">Wydane książki</h2>

        <table class="table">
            <tr>
                <th>Tytuł</th>
                <th>Rok wydania</th>
                <th>Wydawnictwo</th>
            </tr>
        <?php /** @var App\Entities\Book $book */ foreach($params['books'] as $book): ?>
            <tr>
                <td>
                    <a href="<?= BookController::routeUrl('bookDetail', ['id' => $book->getId()]) ?>">
                        <?= $book->title ?>
                    </a>
                </td>
                <td><?= $book->releaseYear ?></td>
                <td><?= $book->publisher ?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>
<!-- end -->
