<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App::getName() ?> | Autorzy</title>

    <?= $this->include('include/styles') ?>
</head>
<body>
    <?= $this->include('include/navbar') ?>

    <div class="container">
        <h1 class="mb-4">Autorzy</h1>

        <div class="row">
            <div class="col-md-3 mb-4">
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
            <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Nazwisko</th>
                            <th>Imię</th>
                        </tr>
                    <?php /** @var App\Entities\Author $author */ foreach($params['authors'] as $author): ?>
                        <tr>
                            <td><?= $author->lastName ?></td>
                            <td><?= $author->firstName ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('include/scripts') ?>
</body>
</html>
