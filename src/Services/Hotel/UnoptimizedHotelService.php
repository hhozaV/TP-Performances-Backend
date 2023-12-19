<?php

namespace App\Services\Hotel;

use App\Common\FilterException;
use App\Common\SingletonTrait;
use App\Common\Singleton;
use App\Common\Timers;
use App\Entities\HotelEntity;
use App\Entities\RoomEntity;
use App\Services\Room\RoomService;
use Exception;
use PDO;

/**
 * Une classe utilitaire pour récupérer les données des magasins stockés en base de données
 */
class UnoptimizedHotelService extends AbstractHotelService {
  
  use SingletonTrait;
  
  protected function __construct () {
    parent::__construct( new RoomService() );
  }
  
  
  /**
   * Récupère une nouvelle instance de connexion à la base de donnée
   *
   * @return PDO
   * @noinspection PhpUnnecessaryLocalVariableInspection
   */
  protected function getDB () : PDO {
    $timer = Timers::getInstance();
    $timerId = $timer->startTimer('getDB');

    $pdo = Singleton::getInstance();

    $timer->endTimer('getDB', $timerId);

    return $pdo;
  }
  
  
  /**
   * Récupère une méta-donnée de l'instance donnée
   *
   * @param int    $userId
   * @param string $key
   *
   * @return string|null
   */

  
  
  /**
   * Récupère toutes les meta données de l'instance donnée
   *
   * @param HotelEntity $hotel
   *
   * @return array
   * @noinspection PhpUnnecessaryLocalVariableInspection
   */
  protected function getMetas ( HotelEntity $hotel ) : array {

    $timer = Timers::getInstance();
    $timerId = $timer->startTimer('getMetas');

    $db = $this->getDB();
    $stmt = $db->prepare( "SELECT meta_key, meta_value FROM `wp_usermeta` WHERE `user_id` = :hotel_id" );
    $stmt->execute(["hotel_id" => $hotel->getId()]);
 
    $result = $stmt->fetchAll(PDO::FETCH_NAMED);
 
    $meta =[];
    foreach($result as $row){
      $meta[$row['meta_key']] = $row['meta_value'];
    }

    $metaDatas = [
      'address' => [
        'address_1' => $meta['address_1'],
        'address_2' => $meta['address_2'],
        'address_city' => $meta['address_city'],
        'address_zip' => $meta['address_zip'],
        'address_country' => $meta['address_country'],
      ],
      'geo_lat' =>  $meta['geo_lat'],
      'geo_lng' =>  $meta['geo_lng'],
      'coverImage' =>  $meta['coverImage'],
      'phone' =>  $meta['address_country'],
    ];

    $timer->endTimer('getMetas', $timerId);

    return $metaDatas;
  }
  
  
  /**
   * Récupère les données liées aux évaluations des hotels (nombre d'avis et moyenne des avis)
   *
   * @param HotelEntity $hotel
   *
   * @return array{rating: int, count: int}
   * @noinspection PhpUnnecessaryLocalVariableInspection
   */
  protected function getReviews ( HotelEntity $hotel ) : array {
    $timer = Timers::getInstance();
    $timerId = $timer->startTimer('getReviews');
    // Récupère tous les avis d'un hotel
    $stmt = $this->getDB()->prepare( "SELECT AVG(meta_value) AS rating, COUNT(meta_value) AS ratingCount FROM wp_posts, wp_postmeta WHERE wp_posts.post_author = :hotelId AND wp_posts.ID = wp_postmeta.post_id AND meta_key = 'rating' AND post_type = 'review';" );
    $stmt->execute( [ 'hotelId' => $hotel->getId() ] );
    $reviews = $stmt->fetchAll( PDO::FETCH_ASSOC )[0];


    $output = [
        'rating' => intval($reviews['rating']),
        'count' => $reviews['ratingCount'],
    ];

    $timer->endTimer('getReviews', $timerId);
    
    return $output;
  }
  
  
  /**
   * Récupère les données liées à la chambre la moins chère des hotels
   *
   * @param HotelEntity $hotel
   * @param array{
   *   search: string | null,
   *   lat: string | null,
   *   lng: string | null,
   *   price: array{min:float | null, max: float | null},
   *   surface: array{min:int | null, max: int | null},
   *   rooms: int | null,
   *   bathRooms: int | null,
   *   types: string[]
   * }                  $args Une liste de paramètres pour filtrer les résultats
   *
   * @throws FilterException
   * @return RoomEntity
   */
  protected function getCheapestRoom ( HotelEntity $hotel, array $args = [] ) : RoomEntity {
    $timer = Timers::getInstance();
    $timerId = $timer->startTimer('getCheapestRoom');

    $query = "
        SELECT post.ID,
          post.post_title AS title,
          MIN(CAST(PriceData.meta_value AS float)) AS price,
          CAST(SurfaceData.meta_value AS int) AS surface,
          TypeData.meta_value AS types,
          CAST(BedroomsCountData.meta_value AS int) AS bedrooms,
          CAST(BathroomsCountData.meta_value AS int) AS bathrooms,
          CoverImageData.meta_value AS coverImage
        
          FROM tp.wp_posts AS post
        
          INNER JOIN tp.wp_postmeta AS SurfaceData
            ON post.ID = SurfaceData.post_id AND SurfaceData.meta_key = 'surface'
          INNER JOIN tp.wp_postmeta AS PriceData
            ON post.ID = PriceData.post_id AND PriceData.meta_key = 'price'     
          INNER JOIN tp.wp_postmeta AS TypeData
            ON post.ID = TypeData.post_id AND TypeData.meta_key = 'type'
          INNER JOIN tp.wp_postmeta AS BedroomsCountData
            ON post.ID = BedroomsCountData.post_id AND BedroomsCountData.meta_key = 'bedrooms_count'
          INNER JOIN tp.wp_postmeta AS BathroomsCountData
            ON post.ID = BathroomsCountData.post_id AND BathroomsCountData.meta_key = 'bathrooms_count'       
          INNER JOIN tp.wp_postmeta AS CoverImageData
            ON post.ID = CoverImageData.post_id AND CoverImageData.meta_key = 'coverImage'
      ";

        $whereClauses[] = "post.post_author = :hotelID AND post.post_type = 'room'";

        if (isset($args['surface']['min']))
            $whereClauses[] = 'SurfaceData.meta_value >= :surfaceMin';

        if (isset($args['surface']['max']))
            $whereClauses[] = 'SurfaceData.meta_value <= :surfaceMax';

        if (isset($args['price']['min']))
            $whereClauses[] = 'PriceData.meta_value >= :priceMin';

        if (isset($args['price']['max']))
            $whereClauses[] = 'PriceData.meta_value <= :priceMax';

        if (isset($args['rooms']))
            $whereClauses[] = 'BedroomsCountData.meta_value >= :roomsBed';

        if (isset($args['bathRooms']))
            $whereClauses[] = 'BathroomsCountData.meta_value >= :bathRooms';

        if (isset($args['types']) && !empty($args['types']))
            $whereClauses[] = "TypeData.meta_value IN('" . implode("', '", $args["types"]) . "')";


        if ($whereClauses != [])
            $query .= " WHERE " . implode(' AND ', $whereClauses);

        $query .= " GROUP BY post.ID;";

        $stmt = $this->getDB()->prepare($query);
        $hotelID = $hotel->getId();
        $stmt->bindParam('hotelID', $hotelID, PDO::PARAM_INT);

        if (isset($args['surface']['min']))
            $stmt->bindParam('surfaceMin', $args['surface']['min'], PDO::PARAM_INT);

        if (isset($args['surface']['max']))
            $stmt->bindParam('surfaceMax', $args['surface']['max'], PDO::PARAM_INT);

        if (isset($args['price']['min']))
            $stmt->bindParam('priceMin', $args['price']['min'], PDO::PARAM_INT);

        if (isset($args['price']['max']))
            $stmt->bindParam('priceMax', $args['price']['max'], PDO::PARAM_INT);

        if (isset($args['rooms']))
            $stmt->bindParam('roomsBed', $args['rooms'], PDO::PARAM_INT);

        if (isset($args['bathRooms']))
            $stmt->bindParam('bathRooms', $args['bathRooms'], PDO::PARAM_INT);

        $stmt->execute();
        if(!($results = $stmt->fetch(PDO::FETCH_ASSOC)))
            throw new FilterException("Aucune chambre ne correspond aux critères.");

        $cheapestRoom = new RoomEntity();
        $cheapestRoom->setId($results['ID']);
        $cheapestRoom->setTitle($results['title']);
        $cheapestRoom->setPrice($results['price']);
        $cheapestRoom->setBathRoomsCount($results['bathrooms']);
        $cheapestRoom->setBedRoomsCount($results['bedrooms']);
        $cheapestRoom->setCoverImageUrl($results['coverImage']);
        $cheapestRoom->setSurface($results['surface']);
        $cheapestRoom->setType($results['types']);
    
    $timer->endTimer('getCheapestRoom', $timerId);

    return $cheapestRoom;
  }
  
  
  /**
   * Calcule la distance entre deux coordonnées GPS
   *
   * @param $latitudeFrom
   * @param $longitudeFrom
   * @param $latitudeTo
   * @param $longitudeTo
   *
   * @return float|int
   */
  protected function computeDistance ( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo ) : float|int {
    return ( 111.111 * rad2deg( acos( min( 1.0, cos( deg2rad( $latitudeTo ) )
          * cos( deg2rad( $latitudeFrom ) )
          * cos( deg2rad( $longitudeTo - $longitudeFrom ) )
          + sin( deg2rad( $latitudeTo ) )
          * sin( deg2rad( $latitudeFrom ) ) ) ) ) );
  }
  
  
  /**
   * Construit une ShopEntity depuis un tableau associatif de données
   *
   * @throws Exception
   */
  protected function convertEntityFromArray ( array $data, array $args ) : HotelEntity {
    $hotel = ( new HotelEntity() )
      ->setId( $data['ID'] )
      ->setName( $data['display_name'] );
    
    // Charge les données meta de l'hôtel
    $metasData = $this->getMetas( $hotel );
    $hotel->setAddress( $metasData['address'] );
    $hotel->setGeoLat( $metasData['geo_lat'] );
    $hotel->setGeoLng( $metasData['geo_lng'] );
    $hotel->setImageUrl( $metasData['coverImage'] );
    $hotel->setPhone( $metasData['phone'] );
    
    // Définit la note moyenne et le nombre d'avis de l'hôtel
    $reviewsData = $this->getReviews( $hotel );
    $hotel->setRating( $reviewsData['rating'] );
    $hotel->setRatingCount( $reviewsData['count'] );
    
    // Charge la chambre la moins chère de l'hôtel
    $cheapestRoom = $this->getCheapestRoom( $hotel, $args );
    $hotel->setCheapestRoom($cheapestRoom);
    
    // Verification de la distance
    if ( isset( $args['lat'] ) && isset( $args['lng'] ) && isset( $args['distance'] ) ) {
      $hotel->setDistance( $this->computeDistance(
        floatval( $args['lat'] ),
        floatval( $args['lng'] ),
        floatval( $hotel->getGeoLat() ),
        floatval( $hotel->getGeoLng() )
      ) );
      
      if ( $hotel->getDistance() > $args['distance'] )
        throw new FilterException( "L'hôtel est en dehors du rayon de recherche" );
    }
    
    return $hotel;
  }
  
  
  /**
   * Retourne une liste de boutiques qui peuvent être filtrées en fonction des paramètres donnés à $args
   *
   * @param array{
   *   search: string | null,
   *   lat: string | null,
   *   lng: string | null,
   *   price: array{min:float | null, max: float | null},
   *   surface: array{min:int | null, max: int | null},
   *   bedrooms: int | null,
   *   bathrooms: int | null,
   *   types: string[]
   * } $args Une liste de paramètres pour filtrer les résultats
   *
   * @throws Exception
   * @return HotelEntity[] La liste des boutiques qui correspondent aux paramètres donnés à args
   */
  public function list ( array $args = [] ) : array {
    $db = $this->getDB();
    $stmt = $db->prepare( "SELECT * FROM wp_users" );
    $stmt->execute();
    
    $results = [];
    foreach ( $stmt->fetchAll( PDO::FETCH_ASSOC ) as $row ) {
      try {
        $results[] = $this->convertEntityFromArray( $row, $args );
      } catch ( FilterException ) {
        // Des FilterException peuvent être déclenchées pour exclure certains hotels des résultats
      }
    }
    
    
    return $results;
  }
}