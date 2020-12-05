<?php if(isset($params['title'])): ?>
<h1 class="mb-4"><?= $params['title'] ?></h1>
<?php endif; ?>

<form action="<?= $params['action'] ?>" method="<?= $params['method'] ?>">
    <?= $params['fields'] ?>
    <button class="btn btn-primary my-2" type="submit">
        <i class="fa fa-<?= $params['submitIcon'] ?? 'check' ?>"></i>&nbsp;
        <?= $params['submit'] ?? 'Prześlij' ?>
    </button>
</form>
