<?php
$appName = App::get()->getConfig('app.name');
$status  = Http\Status::toString(404);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $appName ?> | <?= $status ?></title>

    <?= $this->include('error-style') ?>
</head>
<body>
    <h1><?= $status ?></h1>
    <p>No matching route could be found for the requested URL: <code><?= $params['url'] ?></code></p>
</body>
</html>
