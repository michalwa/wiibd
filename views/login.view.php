<!-- extends base -->

<?php

use Content\Form\Form;

/** @var Form */
$form = $params['form'];

?>

<!-- begin head -->
<title><?= App::getName() ?> | Logowanie</title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <?= $form->html([
                    'title' => 'Logowanie',
                    'submit' => 'Zaloguj'
                ], 'bootstrap-card') ?>

        <?php if($params['error'] ?? 0): ?>
            <div class="alert alert-danger mt-4">
                <i class="fa fa-exclamation-circle"></i>&nbsp;
                Nie udało się zalogować!
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<!-- end -->
