<?php

namespace Drupal\offre\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Offre entity entities.
 *
 * @ingroup offre
 */
interface OffreEntityInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Offre entity name.
   *
   * @return string
   *   Name of the Offre entity.
   */
  public function getName();

  /**
   * Sets the Offre entity name.
   *
   * @param string $name
   *   The Offre entity name.
   *
   * @return \Drupal\offre\Entity\OffreEntityInterface
   *   The called Offre entity entity.
   */
  public function setName($name);

  /**
   * Gets the Offre entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Offre entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Offre entity creation timestamp.
   *
   * @param int $timestamp
   *   The Offre entity creation timestamp.
   *
   * @return \Drupal\offre\Entity\OffreEntityInterface
   *   The called Offre entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Offre entity published status indicator.
   *
   * Unpublished Offre entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Offre entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Offre entity.
   *
   * @param bool $published
   *   TRUE to set this Offre entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\offre\Entity\OffreEntityInterface
   *   The called Offre entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Offre entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Offre entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\offre\Entity\OffreEntityInterface
   *   The called Offre entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Offre entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Offre entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\offre\Entity\OffreEntityInterface
   *   The called Offre entity entity.
   */
  public function setRevisionUserId($uid);

}
