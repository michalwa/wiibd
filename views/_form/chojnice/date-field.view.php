<?php $id = Utils\Uid::next(); ?>

<div class="form-group">
<?php if(isset($params['label'])): ?>
    <label for="<?= $id ?>"><?= $params['label'] ?></label>
<?php endif; ?>
    <input
        class="form-control"
        type="date"
        name="<?= $params['name'] ?>"
        id="<?= $id ?>"
        <?= ($params['required'] ?? false) ? 'required' : '' ?>
        value="<?= $params['value'] ?? '' ?>">
</div>
