<?php

namespace Drupal\poei\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DateFormatter;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "default_block",
 *  admin_label = @Translation("Default block"),
 * )
 */
class DefaultBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Datetime\DateFormatter definition.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;
  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        DateFormatter $date_formatter
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dateFormatter = $date_formatter;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date.formatter')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $date = $this->dateFormatter->format(time(),'html_time');
    $build['default_block']['#markup'] = $this->t("Bienvenue sur notre site. Il est @date",array('@date'=>$date));
    return $build;
  }

}
