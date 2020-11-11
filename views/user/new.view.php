<!-- extends base -->

<?php
use App\Controllers\UserController;

$info = $params['info'] ?? null;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Nowy czytelnik</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <?= $params['form']->html([
                'title' => 'Nowy czytelnik',
                'submit' => 'Dodaj',
            ], 'chojnice') ?>

        <?php if($info === UserController::SUCCESS): ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i>&nbsp;
                Dodano czytelnika!
            </div>
        <?php elseif($info === UserController::ERROR_USERNAME_EXISTS): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                Użytkownik o tej nazwie już istnieje!
            </div>
        <?php elseif($info === UserController::ERROR_PASSWORD_TOO_WEAK): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                Hasło jest zbyt słabe!
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<!-- end -->
