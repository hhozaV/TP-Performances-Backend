<?php /** @noinspection PhpUnused */

namespace App\Entities;

class HotelEntity {
  
  private int $id;
  
  private string $name;
  
  private string $mail;
  
  private array $address;
  
  private string $phone;
  
  private string $geoLat;
  
  private string $geoLng;
  
  private string $imageUrl;
  
  private int $ratingCount;
  
  private int $rating;
  
  private RoomEntity $cheapestRoom;
  
  private ?float $distance;
  
  /**
   * @return int|null
   */
  public function getId () : ?int {
    return $this->id ?? null;
  }
  
  
  /**
   * @param int $id
   *
   * @return HotelEntity
   */
  public function setId ( int $id ) : HotelEntity {
    $this->id = $id;
    
    return $this;
  }
  
  
  /**
   * @return string|null
   */
  public function getName () : ?string {
    return $this->name ?? null;
  }
  
  
  /**
   * @param string $name
   *
   * @return HotelEntity
   */
  public function setName ( string $name ) : HotelEntity {
    $this->name = $name;
    
    return $this;
  }
  
  
  /**
   * @return string|null
   */
  public function getMail () : ?string {
    return $this->mail ?? null;
  }
  
  
  /**
   * @param string $mail
   *
   * @return HotelEntity
   */
  public function setMail ( string $mail ) : HotelEntity {
    $this->mail = $mail;
    
    return $this;
  }
  
  
  /**
   * @return array{
   *     address_1: string,
   *     address_2: string,
   *     address_city: string,
   *     address_zip: string,
   *     address_country: string,
   * }|null
   */
  public function getAddress () : ?array {
    return $this->address ?? null;
  }
  
  
  /**
   * @param array{
   *     address_1: string,
   *     address_2: string,
   *     address_city: string,
   *     address_zip: string,
   *     address_country: string,
   * } $address
   *
   * @return HotelEntity
   */
  public function setAddress ( array $address ) : HotelEntity {
    $this->address = $address;
    
    return $this;
  }
  
  
  /**
   * @return string|null
   */
  public function getPhone () : ?string {
    return $this->phone ?? null;
  }
  
  
  /**
   * @param string $phone
   *
   * @return HotelEntity
   */
  public function setPhone ( string $phone ) : HotelEntity {
    $this->phone = $phone;
    
    return $this;
  }
  
  
  /**
   * @return string|null
   */
  public function getGeoLat () : ?string {
    return $this->geoLat ?? null;
  }
  
  
  /**
   * @param string $geoLat
   *
   * @return HotelEntity
   */
  public function setGeoLat ( string $geoLat ) : HotelEntity {
    $this->geoLat = $geoLat;
    
    return $this;
  }
  
  
  /**
   * @return string|null
   */
  public function getGeoLng () : ?string {
    return $this->geoLng ?? null;
  }
  
  
  /**
   * @param string $geoLng
   *
   * @return HotelEntity
   */
  public function setGeoLng ( string $geoLng ) : HotelEntity {
    $this->geoLng = $geoLng;
    
    return $this;
  }
  
  
  /**
   * @return string|null
   */
  public function getImageUrl () : ?string {
    return $this->imageUrl ?? null;
  }
  
  
  /**
   * @param string $imageUrl
   *
   * @return HotelEntity
   */
  public function setImageUrl ( string $imageUrl ) : HotelEntity {
    $this->imageUrl = $imageUrl;
    
    return $this;
  }
  
  
  /**
   * @return int
   */
  public function getRatingCount () : int {
    return $this->ratingCount;
  }
  
  
  /**
   * @param int $ratingCount
   *
   * @return HotelEntity
   */
  public function setRatingCount ( int $ratingCount ) : HotelEntity {
    $this->ratingCount = $ratingCount;
    
    return $this;
  }
  
  
  /**
   * @return int
   */
  public function getRating () : int {
    return $this->rating;
  }
  
  
  /**
   * @param int $rating
   *
   * @return HotelEntity
   */
  public function setRating ( int $rating ) : HotelEntity {
    $this->rating = $rating;
    
    return $this;
  }
  
  
  /**
   * @return RoomEntity
   */
  public function getCheapestRoom () : RoomEntity {
    return $this->cheapestRoom;
  }
  
  
  /**
   * @param RoomEntity $cheapestRoom
   *
   * @return HotelEntity
   */
  public function setCheapestRoom ( RoomEntity $cheapestRoom ) : HotelEntity {
    $this->cheapestRoom = $cheapestRoom;
    
    return $this;
  }
  
  
  /**
   * @return float|null
   */
  public function getDistance () : ?float {
    return $this->distance ?? null;
  }
  
  
  /**
   * @param float|null $distance
   *
   * @return HotelEntity
   */
  public function setDistance ( ?float $distance ) : HotelEntity {
    $this->distance = $distance;
    
    return $this;
  }
  
  
  /**
   * @return bool
   */
  public function hasDistance() : bool {
    return isset($this->distance);
  }
}