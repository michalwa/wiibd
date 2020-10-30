<?php
/** @var App\Entities\Book */
$book = $params['book'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App::getName() ?> | <?= $book->title ?></title>

    <?= $this->include('include/styles') ?>
</head>
<body>
    <?= $this->include('include/navbar') ?>

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
                                    <li><i class="fa fa-user"></i>
                                        <?= implode(', ', $book->authors) ?></li>
                                    <li><i class="fa fa-building"></i>
                                        <?= $book->publisher ?></li>
                                    <li><i class="fa fa-calendar"></i>
                                        <?= $book->releaseYear ?></li>
                                    <li><i class="fa fa-tag"></i>
                                        <?= implode(', ', $book->genres) ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('include/scripts') ?>
</body>
</html>
