<?php
use App\Controllers\BookController;
use App\Controllers\IndexController;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
    <a href="<?= App::get()->routeUrl(IndexController::class, 'index') ?>"
        class="navbar-brand">
        <?=App::get()->getName() ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="<?= App::get()->routeUrl(BookController::class, 'book_index') ?>"
                    class="nav-link">
                    Książki
                </a>
            </li>
        </ul>
    </div>
</nav>
