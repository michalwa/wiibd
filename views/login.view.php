<?php
use App\Controllers\LoginController;

$error = key_exists('error', $params) ? $params['error'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App::getName() ?> | Logowanie</title>

    <?= $this->include('include/styles') ?>
</head>
<body>
    <?= $this->include('include/navbar') ?>

    <div class="container">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">Zaloguj się</h1>

            <div class="row">
                <div class="col-md-6 mx-auto mb-5">
                    <?= $params['userForm']->html(
                        ['title' => 'Jako czytelnik'],
                        'chojnice-card') ?>
                </div>

                <div class="col-md-6 mx-auto">
                    <?= $params['adminForm']->html(
                        ['title' => 'Jako bibliotekarz'],
                        'chojnice-card') ?>
                </div>
            </div>

            <?php if($error === LoginController::USER_NOT_FOUND): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i>&nbsp;
                    Nie znaleziono użytkownika o podanym loginie!
                </div>
            <?php elseif($error === LoginController::INCORRECT_PASS): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i>&nbsp;
                    Nie udało się zalogować!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?= $this->include('include/scripts') ?>
</body>
</html>
