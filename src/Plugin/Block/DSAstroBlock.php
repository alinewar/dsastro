<?php

namespace Drupal\dsastro\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a astrology block to enter date into.
 *
 * @Block(
 *   id = "dsastro_block",
 *   admin_label = @Translation("Get Astrology"),
 * )
 */
class DSAstroBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Return the form @ Form/DSAstroBlockForm.php.
    return \Drupal::formBuilder()->getForm('Drupal\dsastro\Form\DSAstroBlockForm');
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'Access DS Astrology');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('dsastro_block_settings', $form_state->getValue('dsastro_block_settings'));
  }

}
