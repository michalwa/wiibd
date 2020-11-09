<form action="<?= $params['action'] ?>" method="<?= $params['method'] ?>">
    <?= $params['fields'] ?>
    <button class="btn btn-primary" type="submit">
        <?= $params['submit'] ?? 'Prześlij' ?>
    </button>
</form>
