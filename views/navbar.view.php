<?php

use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\UserSession;

$user = UserSession::user();

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

        <ul class="navbar-nav ml-auto">
        <?php if($user): ?>
            <li class="nav-item mr-2">
                <a href="#" class="nav-link">
                    Witaj, <?= $user->firstName ?>!
                </a>
            </li>
            <li class="nav-item">
            <a href="<?= LoginController::routeUrl('logout') ?>"
                    class="btn btn-danger">
                    Wyloguj
                </a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a href="<?= LoginController::routeUrl('loginForm') ?>"
                    class="btn btn-primary">
                    Zaloguj
                </a>
            </li>
        <?php endif; ?>
        </ul>
    </div>
</nav>
