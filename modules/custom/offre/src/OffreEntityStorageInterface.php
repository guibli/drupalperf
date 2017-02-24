<?php

namespace Drupal\offre;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface OffreEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Offre entity revision IDs for a specific Offre entity.
   *
   * @param \Drupal\offre\Entity\OffreEntityInterface $entity
   *   The Offre entity entity.
   *
   * @return int[]
   *   Offre entity revision IDs (in ascending order).
   */
  public function revisionIds(OffreEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Offre entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Offre entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\offre\Entity\OffreEntityInterface $entity
   *   The Offre entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(OffreEntityInterface $entity);

  /**
   * Unsets the language for all Offre entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
