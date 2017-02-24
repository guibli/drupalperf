<?php

namespace Drupal\offre\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Offre entity entities.
 */
class OffreEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
