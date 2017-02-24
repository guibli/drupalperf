<?php

namespace Drupal\annonce\EventSubscriber;


use Drupal\Core\Database\Connection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Class DefaultSubscriber.
 *
 * @package Drupal\annonce
 */
class DefaultSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;
  protected $currentRoute;
  protected $database;

  /**
   * Constructor.
   */
  public function __construct(AccountProxy $current_user, CurrentRouteMatch $routing,Connection $database) {
    $this->currentUser = $current_user;
    $this->currentRoute = $routing;
    $this->dataBase = $database;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['kernel.request'] = ['kernel_request'];

    return $events;
  }

  /**
   * This method is called whenever the kernel.request event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function kernel_request(Event $event) {

    if($this->currentRoute->getMasterRouteMatch()->getRouteName()=='entity.annonce.canonical' ){
      drupal_set_message('Events register for '.$this->currentUser->getDisplayName(), 'status', TRUE);

      $entity = $this->currentRoute->getParameter('annonce');

      $this->dataBase->upsert('annonce_history')
        ->fields(['hid','uid','nid','update_time'])
        ->values([$this->currentUser->getAccount()->id(). $entity->uuid(),$this->currentUser->getAccount()->id(),$entity->id(),time()])
        ->key('hid')->execute();
    }
  }

}
