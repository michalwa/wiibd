<?php
$appName = App::getConfig('app.name');
$status  = Http\Status::toString(404);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $appName ?> | <?= $status ?></title>

    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Serif:500i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono:400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300&display=swap" rel="stylesheet">

    <?= $this->include('errors/error-style') ?>
</head>
<body>
    <div class="wrapper">
        <h1><?= $status ?></h1>
        <p>No matching route could be found for the requested URL: <code><?= $params['url'] ?></code></p>
    </div>
</body>
</html>
