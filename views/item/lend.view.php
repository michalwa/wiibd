<!-- extends base -->

<?php
use App\Controllers\ItemController;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Wypożycz książkę</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <?= $params['form']->html([
                'title' => 'Wypożycz książkę',
                'submit' => 'Wypożycz',
                'selected' => [
                    'item' => $params['itemId'] ?? null,
                ],
            ], 'chojnice') ?>

        <?php if($params['info'] ?? null === ItemController::SUCCESS): ?>
            <div class="alert alert-success">
                <i class="fa fa-exclamation-check"></i>&nbsp;
                Wypożyczono książkę!
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<!-- end -->
