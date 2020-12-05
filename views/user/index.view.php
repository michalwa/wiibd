<!-- extends base -->

<?php

use App\Auth\UserSession;
use App\Controllers\UserController;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Czytelnicy</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <h1 class="mb-4">Czytelnicy</h1>
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
                <div class="form-group">
                    <label for="classSelect">Klasa</label>
                    <select class="form-control" name="class" id="classSelect">
                        <option value="">Wszystkie</option>
                    <?php foreach($params['classNames'] as $className): ?>
                        <option
                            value="<?= $className ?>"
                            <?= ($params['class'] ?? null) === $className ? 'selected' : '' ?>>
                            <?= $className ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa fa-search"></i>&nbsp;
                    Szukaj
                </button>
            </form>
        <?php if(UserSession::isAdmin()): ?>
            <a href="<?= UserController::routeUrl('newUserForm') ?>"
                class="btn btn-light w-100 mt-4">
                <i class="fa fa-plus"></i>&nbsp;
                Dodaj czytelnika
            </a>
        <?php endif; ?>
        </div>
        <div class="col-lg-9">
            <div class="table-responsive-lg">
                <table class="table">
                    <tr>
                        <th style="width: 2em"></th>
                        <th>Login</th>
                        <th>Nazwisko</th>
                        <th>Imię</th>
                        <th>Klasa</th>
                    </tr>
                <?php /** @var App\Entities\User $user */ foreach($params['users'] as $user): ?>
                <?php if(($params['class'] ?? null) === null || $params['class'] === $user->class): ?>
                <?php $detailUrl = UserController::routeUrl('userDetail', ['id' => $user->getId()]) ?>
                    <tr>
                        <td>
                            <i
                                class="fa fa-xs fa-circle
                                    <?= $user->active ? 'text-success' : 'text-very-muted' ?>"
                                title="<?= $user->active ? 'Aktywny' : 'Nieaktywny' ?>">
                            </i>
                        </td>
                        <td><a href="<?= $detailUrl ?>"><?= $user->username ?></a></td>
                        <td><a href="<?= $detailUrl ?>"><?= $user->lastName ?></a></td>
                        <td><a href="<?= $detailUrl ?>"><?= $user->firstName ?></a></td>
                        <td><?= $user->class ?></td>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end -->
