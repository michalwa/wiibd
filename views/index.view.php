<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App::get()->getName() ?></title>

    <?= $this->include('include/styles') ?>
</head>
<body>
    <?= $this->include('include/navbar') ?>

    <div class="container">
        <h1 class="display-2">Witamy w naszej bibliotece!</h1>
    </div>

    <?= $this->include('include/scripts') ?>
</body>
</html>
