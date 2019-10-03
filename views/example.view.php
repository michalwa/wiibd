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

    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Serif:400,400i,500,500i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono:400,400i&display=swap" rel="stylesheet">
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
                    Query params:
                </p>
                <ul>
                    <?php foreach($request->getQuery() as $key => $value): ?>
                        <li><i><code><?= $key ?></code></i>: <i><code><?= $value ?></code></i>
                    <?php endforeach; ?>
                </ul>
                <p>
                    Include component: <?= $this->include('example-component', ['content' => 'OK']) ?>
                </p>
            </div>

            <div class="card container"></div>
        </div>
    </div>
</body>
</html>
