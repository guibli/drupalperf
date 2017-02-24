<?php

namespace Drupal\poei\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 *
 * @package Drupal\poei\Form
 */
class DefaultForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'poei.default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('poei.default');
    $form['color'] = [
      '#type' => 'select',
      '#title' => $this->t('color'),
      '#description' => $this->t('choose color'),
      '#options' => array('blu' => $this->t('blu'), 'red' => $this->t('red'), 'green' => $this->t('green')),
      '#size' => 3,
      '#default_value' => $config->get('color'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('poei.default')
      ->set('color', $form_state->getValue('color'))
      ->save();
  }

}
