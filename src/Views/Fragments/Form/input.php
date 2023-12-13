<?php
/**
 * @var string       $name
 * @var string|null  $value
 * @var string|null  $label
 * @var string|null  $placeholder
 * @var string|null  $type
 * @var string|null  $id
 * @var boolean|null $labelAsTitle
 * @var boolean|null $hideLabel
 * @var string       $suffix
 */

$id = $id ?? uniqid();
$labelAsTitle = $labelAsTitle ?? false;
$hideLabel = $hideLabel ?? false;

?>
<div>
  <?php if ( $labelAsTitle && ! $hideLabel ) : ?>
    <h3 class="text-slate-600 font-bold">
      <label for="<?= $id ?>">
        <?= $label ?? $name ?>
      </label>
    </h3>
  <?php else : ?>
    <label class="block mb-2 text-sm font-medium text-slate-900 <?= $hideLabel
      ? "sr-only"
      : "" ?>">
      <?= $label ?? $name ?>
    </label>
  <?php endif; ?>
  
  <div class="mt-2 relative">
    <input
      id="<?= $id ?>"
      name="<?= $name ?>"
      type="<?= $type ?? "texte" ?>"
      value="<?= $value ?? "" ?>"
      placeholder="<?= $placeholder ?? "$label..."; ?>"
      class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5 <?= isset($suffix) ? "pr-6" : "" ?>"
    />
    
    <?php if ( isset( $suffix ) ) : ?>
      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-600"><?= $suffix; ?></div>
    <?php endif; ?>
  </div>
</div>
