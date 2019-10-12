<?php
$app          = App::get();
$extendedInfo = $app->getConfig('mode') === 'development';
$appName      = $app->getConfig('app.name');
$status       = Http\Status::toString(500);

if(preg_match('/^Argument (\d+?) passed to (.*?)\(\) must be/', $params['message'], $matches)) {
    $invalidArg = (int)$matches[1] - 1;
    $invalidArgFunc = $matches[2];
}

function __to_string($obj) {
    if(is_object($obj)) {
        return get_class($obj);
    } else if(is_string($obj)) {
        return '"'.stripcslashes($obj).'"';
    } else if(is_array($obj)) {
        $str = '[ ';
        foreach($obj as $item) {
            if($str !== '[ ') $str .= ', ';
            $str .= __to_string($item);
        }
        return $str.' ]';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $appName ?> | <?= Http\Status::toString(500) ?></title>

    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Serif:400i,500i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono:400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300&display=swap" rel="stylesheet">

    <?= $this->include('error-style') ?>

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
            <p>During the handling of the request, an error occured on the server side:</p>

            <!-- <Class> (<code>) in <file>, line <line> -->
            <?php if($params['class'] !== null): ?>
                <code class="error-class"><?= $params['class'] ?></code>
            <?php endif; ?>
            (<span class="error-code"><?= $params['code'] ?></span>)
            in <span class="error-file"><?= $params['file'] ?></span>, line <?= $params['line'] ?>

            <!-- Stack trace -->
            <ul class="error-trace">
                <?php foreach($params['trace'] as $trace): ?>
                    <?php
                    $function = ($trace['class'] ? $trace['class'].'::' : '');
                    $function .= (key_exists('function', $trace) ? $trace['function'] : '');
                    ?>
            
                    <!-- Stack trace entry -->
                    <li class="error-trace-entry">
                        <span class="error-file"><?= $trace['file'] ?></span>, line <?= $trace['line'] ?><br>
                        <code class="error-func"><?= $function ?>(

                        <!-- Arguments -->
                        <?php if(key_exists('args', $trace)): ?>
                            <?php foreach($trace['args'] as $i => $arg): ?>

                                <?php if($invalidArgFunc === $function && $invalidArg === $i): ?>
                                    <span class="invalid-arg"><?= __to_string($arg); ?></span><?= $i === count($trace['args']) - 1 ? '' : ', ' ?>

                                <?php else: ?>
                                    <?= __to_string($arg); ?><?= $i === count($trace['args']) - 1 ? '' : ', ' ?>

                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        )</code>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p class="error-message"><?= $params['message'] ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
