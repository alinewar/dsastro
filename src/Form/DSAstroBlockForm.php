<?php

namespace Drupal\dsastro\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * DS Astrology block form
 */
class DSAstroBlockForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dsastro_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['birthdate'] = array(
      '#type' => 'date',
      '#title' => $this->t('Birth Date'),
      '#default_value' => '03-07-1980',
      '#description' => $this->t('Date for astrology'),
    );

    // Submit.
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Look Up'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $birthdate = $form_state->getValue('birthdate');
    $dateArray = date_parse($birthdate);
    if (!$dateArray) {
      $form_state->setErrorByName('birthdate', $this->t('Not a valid date.'));
    } else {
      if ($dateArray['year'] < 1900 || $dateArray['year'] > 2099) {
        $form_state->setErrorByName('birthdate', $this->t('Year must be between 1900 and 2099'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $birthdate = $form_state->getValue('birthdate');
    $dateArray = date_parse($birthdate);
    $form_state->setRedirect(
      'dsastro.basic',
      array(
        'month' => $dateArray['month'],
        'day' => $dateArray['day'],
        'year' => $dateArray['year'],
      )
    );
  }
}

