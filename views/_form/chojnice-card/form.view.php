<form action="<?= $params['action'] ?>" method="<?= $params['method'] ?>">
    <div class="card">
    <?php if(isset($params['title'])): ?>
        <div class="card-header">
            <h5 class="mb-0"><?= $params['title'] ?></h5>
        </div>
    <?php endif; ?>

        <div class="card-body">
            <?= $params['fields'] ?>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary float-right" type="submit">
                <?= $params['submit'] ?? 'Prześlij' ?>
            </button>
        </div>
    </div>
</form>
