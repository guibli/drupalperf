<?php

/**
 * @file
 * Contains \Drupal\toolbar_menu\Form\SettingsForm.
 */

namespace Drupal\toolbar_menu\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\toolbar_menu\ToolbarMenuManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implement a setting form for toolbar_menu module.
 */
class ToolbarMenuForm extends ConfigFormBase implements ContainerInjectionInterface {

  /**
   * The settings of toolbar_menu configuration.
   *
   * @var array
   *
   * @see \Drupal\Core\Config\Config
   */
  protected $toolbarMenuManager;

  /**
   * {@inheritdoc}
   *
   * @var \Drupal\toolbar_menu\ToolbarMenuManager $toolbar_menu_manager
   */
  public function __construct(ConfigFactoryInterface $config_factory, ToolbarMenuManager $toolbar_menu_manager) {
    parent::__construct($config_factory);

    $this->toolbarMenuManager = $toolbar_menu_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('toolbar_menu.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['toolbar_menu.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'toolbar_menu_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['menus']['#type'] = 'table';
    $form['#description'] = t('Chose the menus you want to display in the Drupal toolbar.');
    $form['menus']['#header'] = array(
      'content' => t('Menu name'),
      'active' => t('View in toolbar'),
      'weight' => t('Weight'),
    );
    $form['menus']['#tabledrag'] = array(
      array(
        'action' => 'order',
        'relationship' => 'sibling',
        'group' => 'menus-order-weight',
      ),
    );
    $form['menus']['#attributes'] = array(
      'id' => 'menus-table',
    );

    foreach ($this->toolbarMenuManager->getEnabledMenus() as $weight => $menu) {
      $form['menus'][$menu->id()] = array(
        '#attributes' => array(
          'class' => 'draggable',
        ),
      );

      $form['menus'][$menu->id()]['content'] = array(
        '#markup' => $menu->label(),
      );

      $form['menus'][$menu->id()]['active'] = array(
        '#type' => 'checkbox',
        '#default_value' => $this->toolbarMenuManager->isActive($menu->id()),
      );

      $form['menus'][$menu->id()]['weight'] = array(
        '#type' => 'weight',
        '#title' => t('Weight'),
        '#title_display' => 'invisible',
        '#default_value' => $weight,
        '#delta' => 50,
        '#attributes' => array('class' => array('menus-order-weight')),
      );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $menu_list = [];
    foreach ($form_state->getValue('menus') as $key => $value) {
      if ($value) {
        $menu_list[$key] = [
          'active' => $value['active'],
          'weight' => $value['weight']
        ];
      }
    }
    $this->toolbarMenuManager->saveSettings($menu_list);
  }

}
