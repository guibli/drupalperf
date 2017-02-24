<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloAccessController extends ControllerBase{

  public function content(){
    $markup = 'INSCRIT DEPUIS PLUS DE 48H';
    return array(
      '#markup' => $markup
    );
  }
}