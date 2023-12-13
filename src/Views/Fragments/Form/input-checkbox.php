<?php
/**
 * @var string      $name
 * @var string|null $value
 * @var bool        $checked
 * @var string      $label
 */
$id = uniqid( "checkbox-" );
?>
<div class="flex flex-row items-center">
  <input
    id="<?= $id ?>"
    name="<?= $name ?>"
    type="checkbox"
    value="<?= $value ?? 1 ?>"
    class="peer w-4 h-4 text-sky-500 bg-slate-100 rounded border-slate-300"
    <?= $checked
      ? "checked"
      : "" ?>
  >
  <label for="<?= $id ?>" class="peer-checked:text-sky-500 peer-checked:font-bold ml-2 text-sm font-medium text-slate-900"><?= $label ?></label>
</div>
