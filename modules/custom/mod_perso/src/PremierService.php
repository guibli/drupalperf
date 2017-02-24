<?php

namespace Drupal\mod_perso;

/**
 * Class PremierService.
 *
 * @package Drupal\mod_perso
 */
class PremierService implements PremierServiceInterface {


  public function premiereFunction($test){
    return '1ER SERVICE '. $test ;
  }
}
