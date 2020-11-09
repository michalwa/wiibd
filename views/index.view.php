<!-- extends base -->

<?php
use App\Controllers\BookController;
?>

<!-- begin head -->
<title><?= App::getName() ?></title>
<!-- end -->

<!-- begin body -->
<div class="container">
    <h1 class="display-2 mb-5">Witamy w naszej bibliotece!</h1>
    <a class="btn btn-primary"
        href="<?= App::routeUrl(BookController::class, 'bookIndex') ?>">
        Przeglądaj książki
    </a>
</div>
<!-- end -->
