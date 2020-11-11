<!-- extends base -->

<?php

use App\Auth\UserSession;
use App\Controllers\BookController;
use App\Controllers\ItemController;
use App\Controllers\PasswordChangeController;
use App\Controllers\UserController;

/** @var App\Entities\User $user */
$user = $params['user'];

$self = isset($params['self']) && $params['self'];
$admin = UserSession::isAdmin();
?>

<!-- begin head -->
<title><?= App::getName() ?> | <?= $user ?></title>
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
                                        class="dropdown-item
                                            <?= ($params['canDelete'] ?? false) ? '' : 'disabled' ?>"
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
                <th colspan="2">Stan</th>
            </tr>
        <?php /** @var App\Entities\Borrow $borrow */ foreach($params['borrows'] as $borrow): ?>
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
                <td>
                <?php if($borrow->active && $borrow->ends < date('Y-m-d')): ?>
                    <span class="text-danger" title="Spóźniony zwrot!">
                        <time class="text-danger"><?= $borrow->ends ?></time>&nbsp;
                        <i class="fa fa-exclamation-triangle"></i>
                    </span>
                <?php else: ?>
                    <time><?= $borrow->ends ?></time>
                <?php endif; ?>
                </td>
            <?php if($borrow->active): ?>
                <td>
                    Wypożyczona
                </td>
            <?php if($admin): ?>
                <td>
                    <a class="btn btn-sm btn-light" href="<?=
                        App::routeUrl(
                            ItemController::class,
                            'returnItem',
                            ['id' => $borrow->item->getId()])?>"
                        data-toggle="danger-confirmation">
                        Zwróć
                    </a>
                </td>
            <?php endif; ?>
            <?php else: ?>
                <td>
                    <span class="text-very-muted">Zwrócona</span>
                </td>
                <td></td>
            <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>
<!-- end -->
