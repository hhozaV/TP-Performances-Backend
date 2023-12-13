<?php

namespace App\Entities;

class RoomEntity {
  
  private int $id;
  
  private string $title;
  
  private string $price;
  
  private string $coverImageUrl;
  
  private int $bedRoomsCount;
  
  private int $bathRoomsCount;
  
  private int $surface;
  
  private string $type;
  
  
  /**
   * @return int
   */
  public function getId () : int {
    return $this->id;
  }
  
  
  /**
   * @param int $id
   *
   * @return RoomEntity
   */
  public function setId ( int $id ) : RoomEntity {
    $this->id = $id;
    
    return $this;
  }
  
  
  /**
   * @return string
   */
  public function getTitle () : string {
    return $this->title;
  }
  
  
  /**
   * @param string $title
   *
   * @return RoomEntity
   */
  public function setTitle ( string $title ) : RoomEntity {
    $this->title = $title;
    
    return $this;
  }
  
  
  /**
   * @return string
   */
  public function getPrice () : string {
    return $this->price;
  }
  
  
  /**
   * @param string $price
   *
   * @return RoomEntity
   */
  public function setPrice ( string $price ) : RoomEntity {
    $this->price = $price;
    
    return $this;
  }
  
  
  /**
   * @return string
   */
  public function getCoverImageUrl () : string {
    return $this->coverImageUrl;
  }
  
  
  /**
   * @param string $coverImageUrl
   *
   * @return RoomEntity
   */
  public function setCoverImageUrl ( string $coverImageUrl ) : RoomEntity {
    $this->coverImageUrl = $coverImageUrl;
    
    return $this;
  }
  
  
  /**
   * @return int
   */
  public function getBedRoomsCount () : int {
    return $this->bedRoomsCount;
  }
  
  
  /**
   * @param int $bedRoomsCount
   *
   * @return RoomEntity
   */
  public function setBedRoomsCount ( int $bedRoomsCount ) : RoomEntity {
    $this->bedRoomsCount = $bedRoomsCount;
    
    return $this;
  }
  
  
  /**
   * @return int
   */
  public function getBathRoomsCount () : int {
    return $this->bathRoomsCount;
  }
  
  
  /**
   * @param int $bathRoomsCount
   *
   * @return RoomEntity
   */
  public function setBathRoomsCount ( int $bathRoomsCount ) : RoomEntity {
    $this->bathRoomsCount = $bathRoomsCount;
    
    return $this;
  }
  
  
  /**
   * @return int
   */
  public function getSurface () : int {
    return $this->surface;
  }
  
  
  /**
   * @param int $surface
   *
   * @return RoomEntity
   */
  public function setSurface ( int $surface ) : RoomEntity {
    $this->surface = $surface;
    
    return $this;
  }
  
  
  /**
   * @return string
   */
  public function getType () : string {
    return $this->type;
  }
  
  
  /**
   * @param string $type
   *
   * @return RoomEntity
   */
  public function setType ( string $type ) : RoomEntity {
    $this->type = $type;
    
    return $this;
  }
  
}