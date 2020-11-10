<!-- extends base -->

<?php
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
        </div>
        <div class="col-lg-9">
            <div class="table-responsive">
                <table class="table " id="books">
                    <tr>
                        <th>Numer inwentarzowy</th>
                        <th>Tytuł</th>
                        <th>Autor</th>
                        <th>Wydawnictwo</th>
                        <th>Rok wydania</th>
                    </tr>
                    <?php /** @var Item $item */ foreach($params['items'] as $item): ?>
                        <tr>
                            <td><?= $item->identifier ?></td>
                            <td><?= $item->book->title ?></td>
                            <td><?= implode(', ', $item->book->authors) ?></td>
                            <td><?= $item->book->publisher ?></td>
                            <td><?= $item->book->releaseYear ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end -->
