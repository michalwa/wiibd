<?php

use App\Controllers\BookController;

$appName = App::get()->getConfig('app.name');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a href="#" class="navbar-brand"><?= $appName ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="<?= App::get()->routeUrl(BookController::class, 'index') ?>" class="nav-link">Książki</a>
            </li>
        </ul>
    </div>
</nav>
