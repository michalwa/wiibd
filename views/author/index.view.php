<!-- extends base -->

<?php
use App\Controllers\AuthorController;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Autorzy</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <h1 class="mb-4">Autorzy</h1>

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
                        <th>Nazwisko</th>
                        <th>Imię</th>
                    </tr>
                <?php /** @var App\Entities\Author $author */ foreach($params['authors'] as $author): ?>
                <?php $detailUrl = App::routeUrl(
                    AuthorController::class,
                    'authorDetail',
                    ['id' => $author->getId()]);
                ?>
                    <tr>
                        <td>
                            <a href="<?= $detailUrl ?>">
                                <?= $author->lastName ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= $detailUrl ?>">
                                <?= $author->firstName ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end -->
