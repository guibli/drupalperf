<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Entity\EntityTypeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelloControllerLastUpdate extends ControllerBase
{

  protected $formatter;
  protected $entityTypeManager;
  public function __construct(DateFormatter $date_formatter,EntityTypeManager $entity_type_manager)
  {
    $this->formatter = $date_formatter;
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container){
    return new static(
      $container->get('date.formatter'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * @return string
   * Return node's title on last update page
   */
  public function newtitle(){
    $nid = \Drupal::routeMatch()->getParameter('node');
    $node =  $this->entityTypeManager->getStorage('node')->load($nid);
    $new_title = $node->getTitle().'\'s last updates';
    return $new_title;
  }
  public function content()
  {
    $nid = \Drupal::routeMatch()->getParameter('node');

    $query = \Drupal::database()
      ->select('hello_node_history','nfh')
      ->fields('nfh',array('uid','update_time'))
      ->condition('nid', $nid)
      ->execute();

    $result = $query->fetchAll();
    $header = array('Author',	'Timestamp');
    foreach ($result as $item) {
      $user =  $this->entityTypeManager->getStorage('user')->load($item->uid);
      $date = $this->formatter->format($item->update_time);
      $parsed['author']=$user->getUsername();
      $parsed['time'] = $date;
      $result_parsed[]=$parsed;
    }
    $table=array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $result_parsed,
    );
    //get node type
    $node = $this->entityTypeManager->getStorage('node')->load($nid);

    //get count
    /*$count_update = \Drupal::database()
      ->select('hello_node_history','nfh')
      ->condition('nid', $nid)
      ->countQuery()
      ->execute();

    $count = $count_update->fetchField();*/

    $render[] = array(
      '#theme' => 'hello',
      '#node_title' => $node->getTitle(),
      '#node_type'=>$node->bundle(),
      '#string_count' =>count($result_parsed),
    );
    $render[] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $result_parsed
    );

    return $render;

  }

}