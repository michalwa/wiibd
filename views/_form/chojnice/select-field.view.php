<?php
$id = Utils\Uid::next();
$multiple = isset($params['multiple']) && $params['multiple'];
$selected = $params['selected'][$params['name']] ?? null;
?>

<div class="form-group">
<?php if(isset($params['label'])): ?>
    <label for="<?= $id ?>"><?= $params['label'] ?></label>
<?php endif; ?>
    <select
        name="<?= $params['name'] ?>"
        id="<?= $id ?>"
        class="form-control"
        <?= $multiple ? 'multiple' : '' ?>>
    <?php foreach($params['options'] as $value => $label): ?>
        <option
            value="<?= $value ?>"
            <?= $value === $selected ? 'selected' : '' ?>>
            <?= $label ?>
        </option>
    <?php endforeach; ?>
    </select>
</div>
