<?php
/**
 * @var string        $name
 * @var bool[]        $values
 * @var string | null $label,
 * @var string[]      $valuesLabels
 */

use function App\Common\render_checkbox;
use function App\Common\slugify;

?>
<div>
  <?php if ( isset( $label ) ) : ?>
    <h3 class="text-slate-600 font-bold">
      <?= $label ?>
    </h3>
  <?php endif; ?>
  
  <ul class="space-y-2 mt-4">
    <?php foreach ( $values as $label => $checked ) : ?>
      <li class="flex items-center">
        <?= render_checkbox(
          $name . '[]',
          $checked,
          $valuesLabels[$label] ?? $label,
          $label
        ); ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
