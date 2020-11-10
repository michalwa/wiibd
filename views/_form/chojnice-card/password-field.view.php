<div class="form-group">
    <?php $id = Utils\Uid::next(); ?>
    <label for="<?= $id ?>"><?= $params['label'] ?></label>
    <input
        class="form-control"
        type="password"
        name="<?= $params['name'] ?>"
        id="<?= $id ?>"
        <?= $params['required'] ? 'required' : '' ?>>
</div>
