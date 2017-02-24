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
function template_preprocess_offre_entity(array &$variables) {
  // Fetch OffreEntity Entity Object.
  $offre_entity = $variables['elements']['#offre_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
