<?php
/**
 * @var HotelEntity $hotel
 */

use App\Entities\HotelEntity;

?>
<div
  class="hotel-card rounded-lg overflow-hidden border border-solide border-slate-100"
  data-lat="<?= $hotel->getGeoLat(); ?>"
  data-lng="<?= $hotel->getGeoLng(); ?>"
>
  <!-- Card image-->
  <div class="bg-sky-50 aspect-video relative">
    <img src="<?= $hotel->getImageUrl(); ?>" alt="Image de l'hôtel" class="w-full aspect-video object-cover object-center" />
    
    <?php if ( $hotel->hasDistance() ) : ?>
      <div class="bg-white absolute right-2 top-2 text-sm text-slate-600 rounded-lg p-2">
        <?= round($hotel->getDistance(), 2); ?>Km
      </div>
    <?php endif; ?>
  </div>
  
  <div class="p-4">
    <!-- Prix par nuit & likes -->
    <div class="flex flex-row justify-between items-center text-slate-600 mb-2">
      <!-- Prix par nuit -->
      <p class="text-lg ">
        <?= $hotel->getCheapestRoom()->getPrice(); ?>€<span class="text-sm">/nuit</span>
      </p>
      
      <!-- Nombre de likes -->
      <p class="flex flex-row items-center">
        <i class="ph-heart text-xl mr-1"></i>
        <?= $hotel->getRating(); ?> (<?= $hotel->getRatingCount() ?>)
      </p>
    </div>
    
    <!-- Nom de l'hôtel -->
    <header class="text-lg font-bold text-slate-900">
      <?= $hotel->getName(); ?>
    </header>
    
    <!-- Localisation -->
    <p>
      <?= $hotel->getAddress()['address_city']; ?>
    </p>
    <p>
      <?= $hotel->getAddress()['address_country']; ?>
    </p>
    
    <footer class="mt-2">
      <ul class="flex flex-row justify-between items-center">
        <!-- Nombre de chambres -->
        <li class="flex flex-row items-center text-slate-400">
          <i class="ph-bed text-slate-300 text-xl mr-1"></i>
          <?= $hotel->getCheapestRoom()->getBedRoomsCount() ?>
        </li>
        
        <!-- Nombre de salles de bain -->
        <li class="flex flex-row items-center text-slate-400">
          <i class="ph-shower text-slate-300 text-xl mr-1"></i>
          <?= $hotel->getCheapestRoom()->getBathRoomsCount() ?>
        </li>
        
        <!-- Surface -->
        <li class="flex flex-row items-center text-slate-400">
          <i class="ph-arrows-out text-slate-300 text-xl mr-1"></i>
          <?= $hotel->getCheapestRoom()->getSurface() ?>m²
        </li>
      </ul>
    </footer>
  </div>
</div>