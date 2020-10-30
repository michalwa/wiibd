<?php
use App\Controllers\BookController;
use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Entities\AdminUser;
use App\Entities\User;
use Utils\Session;

if($id = Session::get('user')) {
    $user = ''.User::getRepository()->findById($id);
} elseif($id = Session::get('admin')) {
    $user = ''.AdminUser::getRepository()->findById($id);
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
            <li class="nav-item">
                <a class="nav-link"
                    href="<?= App::routeUrl(BookController::class, 'bookIndex') ?>">
                    Książki
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <?php if($user !== null): ?>
                <li class="nav-item mr-2">
                    <a class="nav-link"
                        href="#">
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
