<div class="form-group">
    <?php $id = Utils\Uid::next(); ?>
    <label for="<?= $id ?>"><?= $params['label'] ?></label>
    <input
        class="form-control"
        type="text"
        name="<?= $params['name'] ?>"
        id="<?= $id ?>">
</div>
