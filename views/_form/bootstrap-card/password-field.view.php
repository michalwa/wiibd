<div class="form-group">

<?php if(isset($params['label'])): ?>
    <label for="<?= $id = Utils\Uid::next() ?>"><?= $params['label'] ?></label>
<?php endif; ?>

    <input type="password"
        class="form-control"
        id="<?= $id ?? '' ?>"
        name="<?= $params['name'] ?>"
        <?= $params['required'] ? 'required' : '' ?>>

</div>
