<?php

namespace Drupal\annonce\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\Context\ContextDefinition;

/**
* Provides a 'Annonce condition' condition to enable a condition based in module selected status.
*
* @Condition(
*   id = "annonce_condition",
*   label = @Translation("Annonce condition"),
* )
*
*/
class AnnonceCondition extends ConditionPluginBase {

/**
* {@inheritdoc}
*/
public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
{
    return new static(
    $configuration,
    $plugin_id,
    $plugin_definition
    );
}

/**
 * Creates a new ExampleCondition instance.
 *
 * @param array $configuration
 *   The plugin configuration, i.e. an array with configuration values keyed
 *   by configuration option name. The special key 'context' may be used to
 *   initialize the defined contexts by setting it to an array of context
 *   values keyed by context names.
 * @param string $plugin_id
 *   The plugin_id for the plugin instance.
 * @param mixed $plugin_definition
 *   The plugin implementation definition.
 */
 public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

 }

 /**
   * {@inheritdoc}
   */
 public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
   $form['date_debut'] = [
       '#type' => 'date',
       '#title' => $this->t('Select a date'),
       '#default_value' => $this->configuration['date_debut'],
       '#description' => $this->t('Date de debut'),
   ];
   $form['date_fin'] = [
     '#type' => 'date',
     '#title' => $this->t('Select a date'),
     '#default_value' => $this->configuration['date_fin'],
     '#description' => $this->t('Date de fin'),
   ];

   return $form;
 }

/**
 * {@inheritdoc}
 */
 public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
   $this->configuration['date_debut'] = $form_state->getValue('date_debut');
   $this->configuration['date_fin'] = $form_state->getValue('date_fin');

 }

/**
 * {@inheritdoc}
 */
 public function defaultConfiguration() {
   $default['date_debut'] = '';
   $default['date_fin'] = '';
   return $default;
 }

/**
  * Evaluates the condition and returns TRUE or FALSE accordingly.
  *
  * @return bool
  *   TRUE if the condition has been met, FALSE otherwise.
  */
  public function evaluate() {
    $date_debut= $this->configuration['date_debut'];
    $date_fin= $this->configuration['date_fin'];

    if ((strtotime($date_debut) <= time() && strtotime($date_fin) >= time()) ||
      (strtotime($date_debut) <= time() && $date_fin=='') ||
      ($date_debut=='' && strtotime($date_fin) >= time()) ||
      ($date_debut=='' && $date_fin='')
      ) {

      return TRUE;
    }
    kint((strtotime($date_debut) <= time() && strtotime($date_fin) >= time()) ||
      (strtotime($date_debut) <= time() && $date_fin=='') ||
      ($date_debut=='' && strtotime($date_fin) >= time()) ||
      ($date_debut=='' && $date_fin=''));
    return false;

  }

/**
 * Provides a human readable summary of the condition's configuration.
 */
 public function summary()
 {
     $module = $this->getContextValue('module');
     $modules = system_rebuild_module_data();

     $status = ($modules[$module]->status)?t('enabled'):t('disabled');

     return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
 }

}
