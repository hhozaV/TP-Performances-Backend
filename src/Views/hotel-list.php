<?php
/**
 * @var HotelEntity[] $hotels
 * @var array $filters
 * @var array $typesCounters
 */

use App\Entities\HotelEntity;
use function App\Common\get_footer;
use function App\Common\get_header;
use function App\Common\get_template;
use const App\__PROJECT_ROOT__;

echo get_header( [ "title" => "Liste des hôtels" ] );

?>

<form method="get" class="w-full flex flex-row mx-auto">
  
  <div class="lg:w-8/12 w-full flex flex-col">
    <header class="mx-4 py-4 text-slate-500 border-b border-slate-200">
      <h1 class="text-3xl">
        <span class="font-bold">Pretty</span>ButSlow
      </h1>
    </header>
    
    <div class="flex flex-row flex-1">
      <!-- Filters -->
      <aside class="w-3/12 h-full p-4">
        <?= get_template(
          __PROJECT_ROOT__ . "/Views/Fragments/list-filters.php",
          [
            ...$filters,
            'typesCounters' => $typesCounters,
          ]
        ); ?>
      </aside>
      <!-- /Filters -->
      
      <!-- Results -->
      <main class="w-9/12 h-full p-4">
        <header class="flex flex-row items-end justify-between">
          <h2 class="text-2xl font-bold">
            <?= count($hotels); ?> résultats de recherche
          </h2>
        </header>
        
        <section class="grid lg:gap-6 2xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4 my-8">
          <?php
          foreach($hotels as $hotel) :
            echo get_template(
              __PROJECT_ROOT__ . "/Views/Fragments/hotel-card.php",
              ['hotel' => $hotel],
            );
          endforeach;
          ?>
        </section>
      </main>
      <!-- /Results -->
    </div>
  </div>
  
  <!-- Map -->
  <div class="w-4/12 h-screen p-4 lg:block hidden fixed right-0 inset-y-0">
    <div class="relative h-full">
      <input type="hidden" id="lat" name="lat" value="<?= $_GET['lat'] ?? null ?>" />
      <input type="hidden" id="lng" name="lng" value="<?= $_GET['lng'] ?? null ?>" />
      
      <div id="map" class="rounded-lg overflow-hidden h-full z-10"></div>
      
      <!-- Search input-->
      <div class="rounded-xl px-6 bg-white absolute inset-x-10 top-10 z-20 shadow-2xl shadow-slate-400 flex flex-row items-center">
        <i class="ph-magnifying-glass  text-lg text-slate-400"></i>
        
        <label for="map-search" class="sr-only">Recherche</label>
        
        <input
          id="map-search"
          type="text"
          name="search"
          class="p-4 ring-0 border-0 outline-0 flex-1 bg-transparent"
          placeholder="Rechercher..."
          value="<?= $filters['search'] ?>"
        />
        
        <label class="relative w-[130px]">
          <input
            type="text"
            name="distance"
            id="distance"
            placeholder="Distance..."
            class="w-full p-4 ring-0 border-0 outline-0 flex-1 bg-transparent text-right pr-[36px]"
            value="<?= $filters['distance']; ?>"
          />
          <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-600">
            km
          </span>
        </label>
      </div>
      
      <ul id="search-result-autocomplete-list" class="absolute inset-x-10 z-20 bg-white rounded-xl shadow-2xl shadow-slate-400 top-28 overflow-hidden">
      
      </ul>
      
      <!-- Map Controls -->
      <div class="absolute inset-x-10 bottom-10 z-20 flex flex-row items-center space-x-8">
        <!-- Map view -->
        <button type="button" id="map-style-map" class="xl:block hidden btn btn-primary flex-1 shadow-2xl shadow-slate-400">
          Map
        </button>
        
        <!-- Satellite view -->
        <button type="button" id="map-style-satellite" class="xl:block hidden btn flex-1 shadow-2xl shadow-slate-400">
          Satellite
        </button>
        
        <!-- Geolocation -->
        <button type="button" id="map-geoloc" class="rounded-xl btn flex items-center shadow-2xl shadow-slate-400 text-xl h-[3.25rem]">
          <i class="ph-crosshair-fill"></i>
        </button>
        
        <!-- Map Zoom -->
        <div class="rounded-xl py-3 bg-white text-slate-400 flex text-2xl shadow-2xl shadow-slate-400">
          <button id="map-zoom-minus" type="button" class="px-4 flex items-center border-r-2 border-slate-100 md:hover:text-sky-500 transition-colors">
            <i class="ph-minus text-2xl"></i>
          </button>
          <button id="map-zoom-plus" type="button" class="px-4 flex items-center md:hover:text-sky-500 transition-colors">
            <i class="ph-plus text-2xl"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- /Map -->

</form>

<?= get_footer(); ?>
