<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Session\AccountProxy;

/**
 * Provides a hello block
 *
 * @Block(
 *   id = "HelloBlock",
 *   admin_label = @Translation("Hello!")
 * )
 */
class HelloBlock extends BlockBase implements ContainerFactoryPluginInterface{

  protected $formatter;
  protected $current_user;
  public function __construct( DateFormatter $date_formatter,AccountProxy $currentuser,array $configuration, $plugin_id, $plugin_definition)
  {
    $this->formatter = $date_formatter;
    $this->current_user = $currentuser;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container,array $configuration, $plugin_id, $plugin_definition){
    return new static(
      $container->get('date.formatter'),
      $container->get('current_user'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  public function build(){
    $username = $this->current_user->getUsername();
    $date = $this->formatter->format(time(),'html_time');
    $build = array(
      '#markup' => $this->t("Bienvenue sur notre site @username. Il est @date",array('@username' => $username,'@date'=>$date)),
      '#cache' =>array(
        'max-age' => 1000,
        'contexts' => ['user'],
        'tags'  => ['user'],
      ),
    );
    return $build;
  }

}