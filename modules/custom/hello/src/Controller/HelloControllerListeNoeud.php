<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\Core\Link;

class HelloControllerListeNoeud extends ControllerBase{

  public function content($entitytype = NULL ){

    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $query = \Drupal::entityQuery('node');
    if($entitytype) {
      $query->condition('type', $entitytype);
    }
    $ids = $query->pager(15)->execute();
    $entities = $storage->loadMultiple($ids);
    
    foreach ($entities as $item) {
      $list[] = Link::createFromRoute($item->label(),'entity.node.canonical',array('node'=>$item->id()));
      // $list[]=$item->toLink($item->getTitle());
    }

    $render[]=array(
      '#type'=>'pager',
    );
    $render[]=array(
      '#theme' => 'item_list',
      '#items' => $list,
      '#list_type' => 'ol',
    );
    $render[]=array(
      '#type'=>'pager',
    );
    return $render;
  }
}