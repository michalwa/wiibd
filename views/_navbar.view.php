<?php

use App\Controllers\AuthorController;
use App\Controllers\BookController;
use App\Controllers\IndexController;
use App\Controllers\ItemController;
use App\Controllers\LoginController;
use App\Controllers\UserController;
use App\Entities\AdminUser;
use App\Entities\User;
use Utils\Session;

$isadmin = false;

if($id = Session::get('user')) {
    $user = User::getRepository()->findById($id);
} elseif($id = Session::get('admin')) {
    $isadmin = true;
    $user = AdminUser::getRepository()->findById($id);
} else {
    $user = null;
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
    <a href="<?= App::routeUrl(IndexController::class, 'index') ?>"
        class="navbar-brand">
        <?= App::getName() ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item dropdown">
                <a id="navBrowseDropdown"
                    class="nav-link dropdown-toggle"
                    data-toggle="dropdown"
                    role="button"
                    href="#">
                    <i class="fa fa-search"></i>&nbsp;
                    Przeglądaj
                </a>
                <div class="dropdown-menu">

                    <a class="dropdown-item"
                        href="<?= App::routeUrl(BookController::class, 'bookIndex') ?>">
                        <i class="fa fa-book"></i>&nbsp;
                        Książki
                    </a>

                    <a class="dropdown-item"
                        href="<?= App::routeUrl(AuthorController::class, 'authorIndex') ?>">
                        <i class="fa fa-users"></i>&nbsp;
                        Autorzy
                    </a>

                <?php if($isadmin): ?>
                    <a class="dropdown-item"
                        href="<?= App::routeUrl(ItemController::class, 'itemIndex') ?>">
                        <i class="fa fa-cubes"></i>&nbsp;
                        Egzemplarze
                    </a>
                <?php endif; ?>

                </div>
            </li>

        <?php if($isadmin): ?>
            <li class="nav-item dropdown">
                <a id="navAdminDropdown"
                    class="nav-link dropdown-toggle"
                    data-toggle="dropdown"
                    role="button"
                    href="#">
                    <i class="fa fa-cog"></i>&nbsp;
                    Administracja
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="<?= App::routeUrl(UserController::class, 'userIndex') ?>">
                        <i class="fa fa-users"></i>&nbsp;
                        Czytelnicy
                    </a>
                </div>
            </li>
        <?php endif; ?>

        </ul>
        <ul class="navbar-nav ml-auto">
            <?php if($user !== null): ?>

                <li class="nav-item mr-2">
                    <a class="nav-link"
                        href="<?= !$isadmin ? App::routeUrl(UserController::class, 'userDetail', ['id' => $id]) : '#' ?>">
                        <i class="fa fa-user"></i>&nbsp;
                        Witaj, <?= $user ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-danger"
                        href="<?= App::routeUrl(LoginController::class, 'logout') ?>">
                        <i class="fa fa-sign-out-alt"></i>&nbsp;
                        Wyloguj
                    </a>
                </li>

            <?php else: ?>

                <li class="nav-item">
                    <a class="btn btn-primary"
                        href="<?= App::routeUrl(LoginController::class, 'form') ?>">
                        <i class="fa fa-sign-in-alt"></i>&nbsp;
                        Zaloguj
                    </a>
                </li>

            <?php endif; ?>
        </ul>
    </div>
</nav>
