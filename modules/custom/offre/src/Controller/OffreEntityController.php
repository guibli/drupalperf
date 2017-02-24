<?php

namespace Drupal\offre\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\offre\Entity\OffreEntityInterface;

/**
 * Class OffreEntityController.
 *
 *  Returns responses for Offre entity routes.
 *
 * @package Drupal\offre\Controller
 */
class OffreEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Offre entity  revision.
   *
   * @param int $offre_entity_revision
   *   The Offre entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($offre_entity_revision) {
    $offre_entity = $this->entityManager()->getStorage('offre_entity')->loadRevision($offre_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('offre_entity');

    return $view_builder->view($offre_entity);
  }

  /**
   * Page title callback for a Offre entity  revision.
   *
   * @param int $offre_entity_revision
   *   The Offre entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($offre_entity_revision) {
    $offre_entity = $this->entityManager()->getStorage('offre_entity')->loadRevision($offre_entity_revision);
    return $this->t('Revision of %title from %date', array('%title' => $offre_entity->label(), '%date' => format_date($offre_entity->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Offre entity .
   *
   * @param \Drupal\offre\Entity\OffreEntityInterface $offre_entity
   *   A Offre entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OffreEntityInterface $offre_entity) {
    $account = $this->currentUser();
    $langcode = $offre_entity->language()->getId();
    $langname = $offre_entity->language()->getName();
    $languages = $offre_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $offre_entity_storage = $this->entityManager()->getStorage('offre_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $offre_entity->label()]) : $this->t('Revisions for %title', ['%title' => $offre_entity->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all offre entity revisions") || $account->hasPermission('administer offre entity entities')));
    $delete_permission = (($account->hasPermission("delete all offre entity revisions") || $account->hasPermission('administer offre entity entities')));

    $rows = array();

    $vids = $offre_entity_storage->revisionIds($offre_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\offre\OffreEntityInterface $revision */
      $revision = $offre_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $offre_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.offre_entity.revision', ['offre_entity' => $offre_entity->id(), 'offre_entity_revision' => $vid]));
        }
        else {
          $link = $offre_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('offre_entity.revision_revert_translation_confirm', ['offre_entity' => $offre_entity->id(), 'offre_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('offre_entity.revision_revert_confirm', ['offre_entity' => $offre_entity->id(), 'offre_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('offre_entity.revision_delete_confirm', ['offre_entity' => $offre_entity->id(), 'offre_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['offre_entity_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
