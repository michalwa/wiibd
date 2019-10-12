<?php
$app = App::get();
$title = $app->getConfig('app.name') ?? 'Title';
$request = $params['request'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>

    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Serif:500,500i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono:400,400i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $app->getPublicUrl('/css/main.css') ?>">
</head>
<body>
    <div class="container">
        <div class="cards cards-horizontal">
            <div class="card container">
                <h2>Request</h2>
                <p>
                    Route name: <i><code><?= $request->getRouteName() ?></code></i><br>
                    Request URL: <i><code><?= '/'.$request->getPath() ?></code></i><br>
                </p>
                Query params:
                <ul style="list-style: none; padding-left: 20px">
                    <?php foreach($request->getQuery() as $key => $value): ?>
                        <li><i><code><?= $key ?></code></i> = <i><code><?= $value ?></code></i>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card container">
                <h2>Backend</h2>
                <p>
                    PHP Version: <?= PHP_VERSION ?>
                </p>
                <p>
                    Database:
                    <?php if($params['db_ok']): ?>
                        <span class="ok">OK</span>
                    <?php else: ?>
                        <span class="error">Error</span>
                    <?php endif; ?>
                    <br>
                    Name: <?= $params['db_name'] ?>
                </p>
            </div>

            <div class="card container">
                <h2>Views</h2>
                <p>
                    Views directory: <code><?= '/'.App::get()->getConfig('views.dir') ?></code><br>
                    Include view component: <?= $this->include('example-component', ['content' => 'OK']) ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
