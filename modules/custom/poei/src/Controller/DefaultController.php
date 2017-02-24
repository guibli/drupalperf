<?php

namespace Drupal\poei\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxy;

/**
 * Class DefaultController.
 *
 * @package Drupal\poei\Controller
 */
class DefaultController extends ControllerBase {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct(AccountProxy $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {

    $name = $this->currentUser->getAccountName();

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Bienvenue @name',array('@name' => $name)),
    ];
  }

}
