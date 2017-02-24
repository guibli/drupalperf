<?php

namespace Drupal\mod_perso\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\mod_perso\PremierServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "default_block",
 *  admin_label = @Translation("Mon Block Perso"),
 * )
 */
class DefaultBlock extends BlockBase implements ContainerFactoryPluginInterface {
protected $premierService;

  public function __construct(PremierServiceInterface $premier_service,$configuration, $plugin_id, $plugin_definition)
  {
    $this->premierService = $premier_service;
    $this->setConfiguration($configuration);
  }
  public function build() {
    return [
      '#theme' => 'mod_perso',
      '#toto'=>$this->premierService->premiereFunction('TEST'),
    ];
  }
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('mod_perso.premierservice'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }
}
