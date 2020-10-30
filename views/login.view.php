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

                <!-- User form -->
                <div class="col-md-6 mx-auto mb-5">
                    <form action="<?= App::routeUrl(LoginController::class, 'userLogin') ?>" method="post">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Jako czytelnik</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="userUsernameInput">Login</label>
                                    <input id="userUsernameInput"
                                        class="form-control"
                                        type="text"
                                        name="username"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="userPasswordInput">Hasło</label>
                                    <input id="userPasswordInput"
                                        class="form-control"
                                        type="password"
                                        name="password"
                                        required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary float-right" type="submit">Zaloguj się</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Admin form -->
                <div class="col-md-6 mx-auto">
                    <form action="<?= App::routeUrl(LoginController::class, 'adminLogin') ?>" method="post">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Jako bibliotekarz</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="adminUsernameInput">Login</label>
                                    <input id="adminUsernameInput"
                                        class="form-control"
                                        type="text"
                                        name="username"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="adminPasswordInput">Hasło</label>
                                    <input id="adminPasswordInput"
                                        class="form-control"
                                        type="password"
                                        name="password"
                                        required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary float-right" type="submit">Zaloguj się</button>
                            </div>
                        </div>
                    </form>
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
