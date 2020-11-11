<select
    name="<?= $params['name'] ?>"
    <?= isset($params['multiple']) && $params['multiple'] ? 'multiple' : '' ?>>
<?php foreach($params['options'] as $value => $label): ?>
    <option value="<?= $value ?>"><?= $label ?></option>
<?php endforeach; ?>
</select>
