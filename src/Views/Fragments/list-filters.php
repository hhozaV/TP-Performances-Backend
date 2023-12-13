<?php

/**
 * @var bool[] $types
 * @var array $typesCounters
 * @var array{min: int, max: int}|null $price
 * @var array{min: int, max: int}|null $surface
 * @var int|null $rooms
 * @var int|null $bathRooms
 */

use function App\Common\render_checkbox_list;
use function App\Common\render_input;

?>
<div class="space-y-8 sticky top-0">
  <header>
    <h2 class="text-2xl font-bold">
      Filtres
    </h2>
  </header>
  
  <!-- Types -->
  <section>
    <?= render_checkbox_list(
      "types",
      $types,
      "Types",
      $typesCounters
    ); ?>
  </section>
  
  <!-- Prix -->
  <section>
    <h3 class="text-slate-600 font-bold">
      Prix
    </h3>
    
    <div class="xl:flex flex-row xl:space-x-4">
      <div class="xl:w-1/2">
        <?= render_input(
          "price[min]",
          $price['min'] ?? null,
          [
            'label' => "Minimum",
            'hideLabel' => true,
            'type' => 'number',
            'suffix' => '€',
          ]
        ); ?>
      </div>
      
      <div class="xl:w-1/2">
        <?= render_input(
          "price[max]",
          $price['max'] ?? null,
          [
            'label' => "Maximum",
            'hideLabel' => true,
            'type' => 'number',
            'suffix' => '€',
          ]
        ); ?>
      </div>
    </div>
  </section>
  
  <!-- Surface -->
  <section>
    <h3 class="text-slate-600 font-bold">
      Surface
    </h3>
    
    <div class="xl:flex flex-row xl:space-x-4">
      <div class="xl:w-1/2">
        <?= render_input(
          "surface[min]",
          $surface['min'] ?? null,
          [
            'label' => "Minimum",
            'hideLabel' => true,
            'type' => 'number',
            'suffix' => 'm²',
          ]
        ); ?>
      </div>
      
      <div class="xl:w-1/2">
        <?= render_input(
          "surface[max]",
          $surface['max'] ?? null,
          [
            'label' => "Maximum",
            'hideLabel' => true,
            'type' => 'number',
            'suffix' => 'm²',
          ]
        ); ?>
      </div>
  </section>
  
  <!-- Chambres et salles de bain -->
  <section class="flex flex-col 2xl:flex-row space-y-4 2xl:space-x-4 2xl:space-y-0">
    <div class="2xl:w-1/2">
      <?= render_input(
        "rooms",
        $rooms ?? null,
        [
          'label' => "Chambres",
          'labelAsTitle' => true,
          'type' => 'number',
        ]
      ); ?>
    </div>
    
    <div class="2xl:w-1/2">
      <?= render_input(
        "bathRooms",
        $bathRooms ?? null,
        [
          'label' => "Salles de bain",
          'labelAsTitle' => true,
          'type' => 'number',
        ]
      ); ?>
    </div>
  </section>
  
  <!-- Submit -->
  <section class="flex flex-row items-stretch space-x-4">
    <a href="/" class="btn flex items-center">
      <i class="ph-arrow-counter-clockwise"></i>
    </a>
    <button type="submit" class="btn btn-primary flex-1 text-center">
      Chercher
    </button>
  </section>
</div>