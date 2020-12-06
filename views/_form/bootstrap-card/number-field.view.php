<div class="form-group">

<?php if(isset($params['label'])): ?>
    <label for="<?= $id = Utils\Uid::next() ?>"><?= $params['label'] ?></label>
<?php endif; ?>

    <input type="number"
        class="form-control"
        id="<?= $id ?? '' ?>"
        name="<?= $params['name'] ?>"
        <?= $params['min'] ? "min='{$params['min']}'" : '' ?>
        <?= $params['max'] ? "max='{$params['max']}'" : '' ?>
        <?= $params['required'] ? 'required' : '' ?>>

</div>
