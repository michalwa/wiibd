<!-- extends base -->

<?php

use App\Auth\UserSession;
use App\Controllers\BookController;
use App\Controllers\ItemController;
use App\Controllers\PasswordChangeController;
use App\Controllers\UserController;

/** @var App\Entities\User $user */
$user = $params['user'];

$borrows = $params['borrows']->toArray();

$self = isset($params['self']) && $params['self'];
$admin = UserSession::isAdmin();
$canDelete = count($borrows) === 0;
?>

<!-- begin head -->
<title><?= App::getName() ?> | <?= $user ?></title>
<link rel="stylesheet" href="<?= App::getPublicUrl('css/product-details.css') ?>">
<link rel="stylesheet" href="<?= App::getPublicUrl('css/common.css') ?>">
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
                            <h2><?= $user ?></h2>

                        <?php if($self || $admin): ?>
                            <div class="dropdown float-right">
                                <button
                                    class="btn"
                                    type="button"
                                    id="cogMenuButton"
                                    data-toggle="dropdown">
                                    <i class="fa fa-lg fa-cog"></i>
                                </button>
                                <div class="dropdown-menu">

                                <?php if($self): ?>
                                    <a href="<?= App::routeUrl(PasswordChangeController::class, 'form'); ?>"
                                        class="dropdown-item">
                                        Zmień hasło
                                    </a>
                                <?php elseif($admin): ?>
                                    <a href="<?=
                                        App::routeUrl(
                                            UserController::class,
                                            'deleteUser',
                                            ['id' => $user->getId()]); ?>"
                                        class="dropdown-item <?= $canDelete ? '' : 'disabled' ?>"
                                        data-toggle="danger-confirmation">
                                        Usuń czytelnika
                                    </a>
                                <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                            <ul class="product-details">
                                <li>
                                    <i
                                        class="fa fa-xs fa-circle
                                            <?= $user->active ? 'text-success' : 'text-very-muted' ?>"
                                        title="<?= $user->active ? 'Aktywny' : 'Nieaktywny' ?>">
                                    </i>
                                    <?= $user->username ?>
                                </li>
                                <li>
                                    <i class="fa fa-users" title="Klasa"></i>
                                    <?= $user->class ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <h2 class="mb-4">Wypożyczone książki</h2>

        <table class="table">
            <tr>
                <th>Numer egzemplarza</th>
                <th>Tytuł</th>
                <th>Data wypożyczenia</th>
                <th>Data oddania</th>
                <th></th>
            </tr>
        <?php /** @var App\Entities\Borrow $borrow */ foreach($borrows as $borrow): ?>
            <tr>
                <td><?= $borrow->item->identifier ?></td>
                <td>
                    <a href="<?=
                        App::routeUrl(
                            BookController::class,
                            'bookDetail',
                            ['id' => $borrow->item->book->getId()])
                    ?>">
                        <?= $borrow->item->book->title ?>
                    </a>
                </td>
                <td><time><?= $borrow->began ?></time></td>
                <td><time><?= $borrow->ends ?></time></td>
                <td>
                    <a class="btn btn-light" href="<?=
                        App::routeUrl(
                            ItemController::class,
                            'returnItem',
                            ['id' => $borrow->item->getId()])
                    ?>">
                        Zwróć
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>
<!-- end -->
