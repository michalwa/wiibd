<!-- extends base -->

<?php
use App\Controllers\AuthorController;

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
        <div class="col-md">
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
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end -->
