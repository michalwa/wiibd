<!-- extends base -->

<?php
use App\Controllers\PasswordChangeController;

$info = $params['info'] ?? null;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Zmień hasło</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="col-lg-4 mx-auto">
        <div class="row mb-4">
            <div class="col-12">
                <?=
                $params['form']->html([
                    'title' => 'Zmień hasło',
                    'submit' => 'Zmień hasło'
                ], 'chojnice-card')
                ?>
            </div>
        </div>

    <?php if($info === PasswordChangeController::ERROR_PASSWORDS_DONT_MATCH): ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i>&nbsp;
            Podane hasła różnią się!
        </div>
    <?php elseif($info === PasswordChangeController::ERROR_INVALID_PASSWORD): ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i>&nbsp;
            Błędne hasło!
        </div>
    <?php elseif($info === PasswordChangeController::ERROR_PASSWORD_TOO_WEAK): ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>&nbsp;
            Hasło jest zbyt słabe!
        </div>
    <?php elseif($info === PasswordChangeController::SUCCESS): ?>
        <div class="alert alert-success">
            <i class="fa fa-check"></i>&nbsp;
            Hasło zostało zmienione!
        </div>
    <?php endif; ?>
    </div>
</div>
<!-- end -->
