<?php

namespace Drupal\yamlform_ui\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\yamlform\Utility\YamlFormDialogHelper;
use Drupal\yamlform\YamlFormInterface;

/**
 * Provides a select element type form for a form element.
 */
class YamlFormUiElementTypeSelectForm extends YamlFormUiElementTypeFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'yamlform_ui_element_type_select_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, YamlFormInterface $yamlform = NULL) {
    $parent = $this->getRequest()->query->get('parent');

    $headers = [
      ['data' => $this->t('Element')],
      ['data' => $this->t('Category')],
      ['data' => $this->t('Operations')],
    ];

    $elements = $this->elementManager->getInstances();
    $definitions = $this->getDefinitions();
    $rows = [];
    foreach ($definitions as $plugin_id => $plugin_definition) {
      /** @var \Drupal\yamlform\YamlFormElementInterface $yamlform_element */
      $yamlform_element = $elements[$plugin_id];

      // Skip disabled or hidden plugins.
      if ($yamlform_element->isDisabled() || $yamlform_element->isHidden()) {
        continue;
      }

      // Skip wizard page which has a dedicated URL.
      if ($plugin_id == 'yamlform_wizard_page') {
        continue;
      }

      $route_parameters = ['yamlform' => $yamlform->id(), 'type' => $plugin_id];
      $route_options = ($parent) ? ['query' => ['parent' => $parent]] : [];
      $row = [];
      $row['title']['data'] = [
        '#type' => 'inline_template',
        '#template' => '<div class="yamlform-form-filter-text-source">{{ label }}</div>',
        '#context' => [
          'label' => $plugin_definition['label'],
        ],
      ];
      $row['category']['data'] = $plugin_definition['category'];
      $row['operations']['data'] = [
        '#type' => 'operations',
        '#links' => [
          'add' => [
            'title' => $this->t('Add element'),
            'url' => Url::fromRoute('entity.yamlform_ui.element.add_form', $route_parameters, $route_options),
            'attributes' => YamlFormDialogHelper::getModalDialogAttributes(800),
          ],
        ],
      ];
      // Issue #2741877 Nested modals don't work: when using CKEditor in a
      // modal, then clicking the image button opens another modal,
      // which closes the original modal.
      // @todo Remove the below workaround once this issue is resolved.
      if ($yamlform_element->getPluginId() == 'processed_text') {
        unset($row['operations']['data']['#links']['add']['attributes']);
      }
      $rows[] = $row;
    }

    $form['#attached']['library'][] = 'yamlform/yamlform.form';

    $form['filter'] = [
      '#type' => 'search',
      '#title' => $this->t('Filter'),
      '#title_display' => 'invisible',
      '#size' => 30,
      '#placeholder' => $this->t('Filter by element name'),
      '#attributes' => [
        'class' => ['yamlform-form-filter-text'],
        'data-element' => '.yamlform-ui-element-type-table',
        'title' => $this->t('Enter a part of the element name to filter by.'),
        'autofocus' => 'autofocus',
      ],
    ];

    $form['elements'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No element available.'),
      '#attributes' => [
        'class' => ['yamlform-ui-element-type-table'],
      ],
    ];

    return $form;
  }

}
