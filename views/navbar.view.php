<?php
use App\Controllers\IndexController;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
    <a href="<?= IndexController::routeUrl('index') ?>"
        class="navbar-brand">
        <?= App::getName() ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link"
                    href="<?= IndexController::routeUrl('index') ?>">
                    Strona główna</a>
            </li>
        </ul>
    </div>
</nav>
