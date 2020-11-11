<?php $id = Utils\Uid::next(); ?>

<div class="form-group">
<?php if(isset($params['label'])): ?>
    <label for="<?= $id ?>"><?= $params['label'] ?></label>
<?php endif; ?>
    <input
        class="form-control"
        type="text"
        name="<?= $params['name'] ?>"
        id="<?= $id ?>"
        value="<?= $params['value'] ?? '' ?>"
        <?= $params['required'] ? 'required' : '' ?>
        <?= ($params['valid'] ?? true) ? '' : 'is-invalid' ?>>
</div>
