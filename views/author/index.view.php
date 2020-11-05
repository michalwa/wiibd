<?php
use App\Controllers\UserController;
?>

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

    <?= $this->include('include/scripts') ?>
</body>
</html>
