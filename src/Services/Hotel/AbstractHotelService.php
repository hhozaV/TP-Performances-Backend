<?php

namespace App\Services\Hotel;

use App\Entities\HotelEntity;
use App\Services\Room\AbstractRoomService;

abstract class AbstractHotelService {
  
  protected AbstractRoomService $roomService;
  
  public function getRoomService() : AbstractRoomService {
    return $this->roomService;
  }
  
  public function __construct ( AbstractRoomService $roomService ) {
    $this->roomService = $roomService;
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
   * @return HotelEntity[] La liste des boutiques qui correspondent aux paramètres donnés à args
   */
  abstract public function list( array $args = [] ) : array;
  
}