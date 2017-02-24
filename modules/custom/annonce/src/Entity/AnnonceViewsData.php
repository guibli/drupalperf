<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;


/**
 * Provides the views data for the Annonce entity type.
 */
class AnnonceViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['annonce_history']['table']['base'] = array(
      'field' => 'hid',
      'title' => t('Annonce history'),
      'help' => t('The annonce history ID.'),
    );
    $data['annonce_history']['table']['group'] = t('Annonce');
    $data['annonce_history']['hid'] = array(
      'title' => t('history unique ref'),
      'field' => array(
        'id' => 'standard',
        'click sortable' => TRUE,
      ),
      'filter' => array(
        'handler' => 'markup',
      ),
    );

    $data['annonce_history']['nid'] = array(
      'title' => t('history annonce id'),
      'field' => array(
        'id' => 'standard',
        'click sortable' => TRUE,
      ),
      'filter' => array(
        'handler' => 'rendered_entity',
      ),
      'relationship' => array(
        // Views name of the table to join to for the relationship.
        'base' => 'annonce',
        // Database field name in the other table to join on.
        'base field' => 'id',
        // ID of relationship handler plugin to use.
        'id' => 'standard',
        // Default label for relationship in the UI.
        'label' => t('relational annonce'),
      ),
    );

    $data['annonce_history']['uid'] = array(
      'title' => t('history user id'),
      'field' => array(
        'id' => 'standard',
        'click sortable' => TRUE,
      ),
      'filter' => array(
        'handler' => 'user_data',
      ),
      'relationship' => array(
        // Views name of the table to join to for the relationship.
        'base' => 'users_field_data',
        // Database field name in the other table to join on.
        'base field' => 'uid',
        // ID of relationship handler plugin to use.
        'id' => 'standard',
        // Default label for relationship in the UI.
        'label' => t('relational user'),
      ),
    );

    $data['annonce_history']['update_time'] = array(
      'title' => t('history timestamp last visit'),
      'field' => array(
        'id' => 'date',
        'click sortable' => TRUE,
      ),
      'filter' => array(
        'handler' => 'date',
      ),
    );

    $data['annonce']['table']['join'] = array(
      'annonce_history' => array(
        // Primary key field in node_field_data to use in the join.
        'left_field' => 'id',
        // Foreign key field in example_table to use in the join.
        'field' => 'nid',
        // 'extra' is an array of additional conditions on the join.
        ),
      );

    $data['users_field_data']['table']['join'] = array(
      'annonce_history' => array(
        // Primary key field in node_field_data to use in the join.
        'left_field' => 'uid',
        // Foreign key field in example_table to use in the join.
        'field' => 'uid',
        // 'extra' is an array of additional conditions on the join.
      ),
    );

    return $data;
  }

}
