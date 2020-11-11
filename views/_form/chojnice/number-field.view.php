<?php
$id = Utils\Uid::next();
?>
<div class="form-group">
<?php if(isset($params['label'])): ?>
    <label for="<?= $id ?>"><?= $params['label'] ?></label>
<?php endif; ?>
    <input
        type="number"
        name="<?= $params['name'] ?>"
        id="<?= $id ?>"
        class="form-control"
        <?= isset($params['required']) && $params['required'] ? 'required' : '' ?>
        min="<?= $params['min'] ?? '' ?>"
        max="<?= $params['max'] ?? '' ?>">
</div>
