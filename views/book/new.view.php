<!-- extends base -->

<?php
use App\Controllers\BookController;
?>

<!-- begin head -->
<title><?= App::getName() ?> | Nowa książka</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <?= $params['form']->html([
                'title' => 'Nowa książka',
                'submit' => 'Dodaj',
            ], 'chojnice') ?>

        <?php if($params['info'] ?? null === BookController::SUCCESS): ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i>&nbsp;
                Dodano książkę!
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<!-- end -->
