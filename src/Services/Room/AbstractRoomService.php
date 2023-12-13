<?php

namespace App\Services\Room;

abstract class AbstractRoomService {
  
  /**
   * @return array{
   *     Appartement: int,
   *     Maison: int,
   *     Chambre: int
   * }
   */
  abstract public function getCountByType() : array;
  
}