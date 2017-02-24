<?php

namespace Drupal\offre;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\offre\Entity\OffreEntityInterface;

/**
 * Defines the storage handler class for Offre entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Offre entity entities.
 *
 * @ingroup offre
 */
class OffreEntityStorage extends SqlContentEntityStorage implements OffreEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(OffreEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {offre_entity_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {offre_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(OffreEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {offre_entity_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('offre_entity_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
