<?php
$extendedInfo = App::getConfig('mode') === 'development';
$status       = Http\Status::toString(500);

$invalidArgFunc = null;
$invalidArg = -1;
if(preg_match('/^Argument (\d+?) passed to (.*?)\(\) must be/', $params['message'], $matches)) {
    $invalidArg = (int)$matches[1] - 1;
    $invalidArgFunc = $matches[2];
}

$message = $params['message'];
if($params['class'] === 'TypeError') {
    $message = preg_replace(
        '/passed to (.*?) must be/',
        'passed to <code>$1</code> must be',
        $message);

    $message = preg_replace(
        '/must be of the type (.+?), (.+?) /',
        'must be of type <code>$1</code>, <code>$2</code> ',
        $message);

    $message = preg_replace(
        '/must be an instance of (.+?), (instance of )?(.+?) returned/',
        'must be an instance of <code>$1</code>, $2<code>$3</code> returned',
        $message);

    $message = preg_replace(
        '/called in (.*?) on line/',
        'called in <span class="error-file">$1</span> on line',
        $message);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= App::getConfig('app.name') ?> | <?= Http\Status::toString(500) ?></title>

    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Serif:500i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono:400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300&display=swap" rel="stylesheet">

    <?= $this->include('errors/error-style') ?>

    <style>
        .error-class {
            background: #fcc;
            color: #700;
        }

        .error-message {
            margin-top: 20px;
        }

        .error-file {
            text-decoration: underline;
        }

        .error-trace {
            list-style: none;
        }

        .error-trace-entry {
            margin-bottom: 10px;
        }

        .error-trace-entry:before {
            content: 'in';
            display: inline-block;
            margin-left: -1.5em;
            width: 1.5em;
        }

        .invalid-arg {
            text-decoration: red wavy underline;
            text-decoration-skip-ink: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1><?= $status ?></h1>

        <?php if($extendedInfo): ?>
            <p>Podczas przetwarzania żądania, serwer napotkał problem:</p>

            <!-- <Class> (<code>) in <file>, line <line> -->
            <?php if($params['class'] !== null): ?>
                <code class="error-class"><?= $params['class'] ?></code>
            <?php endif; ?>
            (<span class="error-code"><?= $params['code'] ?></span>)
            in <span class="error-file"><?= $params['file'] ?></span>, line <?= $params['line'] ?>

            <!-- Message -->
            <p class="error-message"><?= $message ?></p>

            <!-- Stack trace -->
            <ul class="error-trace">
                <?php foreach($params['trace'] as $trace): ?>
                    <?php
                    $function = (key_exists('class', $trace) && $trace['class'] ? $trace['class'].'::' : '');
                    $function .= (key_exists('function', $trace) ? $trace['function'] : '');
                    ?>

                    <!-- Stack trace entry -->
                    <li class="error-trace-entry">
                        <?php if(key_exists('file', $trace)): ?>
                            <span class="error-file"><?= $trace['file'] ?></span>, line <?= $trace['line'] ?><br>
                        <?php endif; ?>
                        <code class="error-func-call"><?= $function ?>(

                        <!-- Arguments -->
                        <?php if(key_exists('args', $trace)): ?>
                            <?php foreach($trace['args'] as $i => $arg): ?>

                                <?php if($invalidArgFunc === $function && $invalidArg === $i): ?>
                                    <span class="invalid-arg"><?= htmlentities(stringify($arg)); ?><?= $i === count($trace['args']) - 1 ? '</span>' : ',</span> ' ?>

                                <?php else: ?>
                                    <?= htmlentities(stringify($arg)); ?><?= $i === count($trace['args']) - 1 ? '' : ', ' ?>

                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        )</code>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
