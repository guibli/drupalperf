<?php

namespace Drupal\offre;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Offre entity entity.
 *
 * @see \Drupal\offre\Entity\OffreEntity.
 */
class OffreEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\offre\Entity\OffreEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished offre entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published offre entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit offre entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete offre entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add offre entity entities');
  }

}
