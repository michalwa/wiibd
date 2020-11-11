<!-- extends base -->

<?php
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
                <div class="input-group">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-search"></i>&nbsp;
                        Szukaj
                    </button>
                </div>
            </form>
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
                    <?php
                    $detailUrl = App::routeUrl(
                        UserController::class,
                        'userDetail',
                        ['id' => $user->getId()])
                    ?>
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
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end -->
