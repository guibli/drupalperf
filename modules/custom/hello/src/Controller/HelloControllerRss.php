<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class HelloControllerRss extends ControllerBase{

  public function content(){
    $response = new Response();
    $response->setContent('<xml>bleh</xml>');
    return ($response);
  }
}