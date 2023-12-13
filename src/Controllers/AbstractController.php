<?php

namespace App\Controllers;

/**
 * Définit les comportements par défaut des contrôleurs
 */
abstract class AbstractController {
  
  abstract function render() : void;
  
}