<!-- extends base -->

<?php
use App\Controllers\ItemController;

$info = $params['info'] ?? null;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Nowe egzemplarze</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <?= $params['form']->html([
                'title' => 'Nowe egzemplarze',
                'submit' => 'Dodaj',
            ], 'chojnice') ?>

        <?php if($info === ItemController::SUCCESS): ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i>&nbsp;
                Dodano egzemplarze!
            </div>
        <?php elseif($info === ItemController::ERROR_IDENTIFIER_EXISTS): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                Podany numer już istnieje!
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<!-- end -->
