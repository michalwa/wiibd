<!-- extends base -->

<?php

use App\Auth\UserSession;
use App\Controllers\BookController;
use App\Controllers\ItemController;
use App\Controllers\UserController;
use App\Entities\Borrow;
use App\Entities\Item;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Egzemplarze</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <h1 class="mb-4">Egzemplarze</h1>
    <div class="row">
        <div class="col-lg-3 mb-4">
            <form action="#" method="get">
                <div class="input-group mb-2">
                    <input
                        class="form-control"
                        id="searchInput"
                        type="search"
                        name="search"
                        placeholder="Szukaj"
                        value="<?= htmlescape($params['search'] ?? '') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-search"></i>&nbsp;
                        Szukaj
                    </button>
                </div>
            </form>
        <?php if(UserSession::isAdmin()): ?>
            <a href="<?= ItemController::routeUrl('newItemsForm') ?>"
                class="btn btn-light w-100 mt-4">
                <i class="fa fa-plus"></i>&nbsp;
                Dodaj egzemplarze
            </a>
        <?php endif; ?>
        </div>
        <div class="col-lg-9">
            <div class="table-responsive-lg">
                <table class="table table-sm" id="books">
                    <tr>
                        <th class="text-center align-middle">Numer inwentarzowy</th>
                        <th class="text-center align-middle">Tytuł</th>
                        <th class="text-center align-middle">Autor</th>
                        <th class="text-center align-middle" colspan="2">Stan</th>
                    </tr>
                    <?php /** @var Item $item */ foreach($params['items'] as $item): ?>
                        <tr id="item-<?= $item->identifier ?>">
                            <td class="align-baseline"><?= $item->identifier ?></td>
                            <td class="align-baseline">
                                <a href="<?= BookController::routeUrl('bookDetail', ['id' => $item->book->getId()]) ?>">
                                    <?= $item->book->title ?>
                                </a>
                            </td>
                            <td class="align-baseline"><?= implode(', ', $item->book->authors) ?></td>
                        <?php if(($borrow = Borrow::findActiveByItemId($item->getId())) === null): ?>
                            <td class="align-baseline">
                                <span class="text-success">Dostępny</span>
                            </td>
                            <td class="align-baseline">
                                <a href="<?= ItemController::routeUrl('lendForm', [], ['item' => $item->getId()]) ?>"
                                    class="btn btn-sm w-100 btn-light">
                                    Wypożycz
                                </a>
                            </td>
                        <?php else: ?>
                            <td class="align-baseline">
                                <span class="text-danger">
                                    Wypożyczona&nbsp;przez<br>
                                    <a href="<?= UserController::routeUrl('userDetail', ['id' => $borrow->user->getId()]) ?>">
                                        <?= $borrow->user ?>
                                    </a>
                                </span>
                            </td>
                            <td class="align-baseline">
                                <a href="<?= ItemController::routeUrl('returnItem', ['id' => $item->getId()]) ?>"
                                    class="btn btn-sm w-100 btn-light"
                                    data-toggle="danger-confirmation">
                                    Zwróć
                                </a>
                            </td>
                        <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end -->
