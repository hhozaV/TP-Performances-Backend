<?php

use function App\Common\get_footer;
use function App\Common\get_header;

echo get_header( [ 'title' => "404 : Page non trouvée" ] );

?>
  <main class="container mx-auto flex flex-col min-h-screen justify-center items-center">
    
    <div class="w-full mb-12">
      <div class="tenor-gif-embed" data-postid="25105508" data-share-method="host" data-aspect-ratio="1.75824" data-width="100%"><a href="https://tenor.com/view/confused-john-travolta-gif-25105508">Confused John Travolta GIF</a>from <a href="https://tenor.com/search/confused-gifs">Confused GIFs</a>
      </div>
      <script type="text/javascript" async src="https://tenor.com/embed.js"></script>
    </div>
    
    <h1 class="mb-2 text-slate-400 text-center text-3xl">
      404
    </h1>
    <a href="/" class="text-blue-500 font-medium text-lg hover:underline">Retourner à l'accueil</a>
  
  </main>
<?php echo get_footer(); ?>