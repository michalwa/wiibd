<?php
$app = App::get();
$title = $app->getConfig('app.name') ?? '';
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
        <?php if(is_empty($params['dummies'])): ?>
            No entries
        <?php else: ?>
            <ul>
                <?php foreach($params['dummies'] as $dummy): ?>
                    <li><?= $dummy->name ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
