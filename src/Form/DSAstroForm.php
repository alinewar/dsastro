<?php

namespace Drupal\dsastro\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class DSAstroForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dsastro_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('dsastro.settings');

    // Page title field.
    $form['page_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('DS Astro page title:'),
      '#default_value' => $config->get('dsastro.page_title'),
      '#description' => $this->t('Give your DS Astro page a title.'),
    );
    // Source text field.
    $form['page_text'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('DS Astro page message:'),
      '#default_value' => $config->get('dsastro.page_text'),
      '#description' => $this->t('Message to display.'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('dsastro.settings');
    $config->set('dsastro.page_text', $form_state->getValue('page_text'));
    $config->set('dsastro.page_title', $form_state->getValue('page_title'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dsastro.settings',
    ];
  }

}
