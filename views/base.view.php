<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?= $this->include('include/styles') ?>

    <!-- slot head -->
</head>
<body>
    <?= $this->include('include/navbar') ?>

    <!-- slot body -->

    <?= $this->include('include/scripts') ?>
</body>
</html>
