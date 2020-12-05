<form action="<?= $params['action'] ?>" method="<?= $params['method'] ?>">
    <?= $params['fields'] ?>
    <button type="submit">
        <?= $params['submit'] ?? 'Prześlij' ?>
    </button>
</form>
