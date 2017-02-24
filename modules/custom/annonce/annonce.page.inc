<?php

/**
 * @file
 * Contains offre_entity.page.inc.
 *
 * Page callback for Offre entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Offre entity templates.
 *
 * Default template: offre_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_annonce(array &$variables) {
  // Fetch OffreEntity Entity Object.
  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }

}
