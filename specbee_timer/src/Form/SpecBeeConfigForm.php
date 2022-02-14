<?php

namespace Drupal\specbee_timer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Configure Timezone.
 */
class SpecBeeConfigForm extends ConfigFormBase {

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructor of class.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'specbee_timer';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['specbee_timer.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('specbee_timer.settings');

    $form['filters'] = [
      '#type'  => 'fieldset',
      '#title' => 'Time Zone Configuration',
      '#open'  => TRUE,
    ];
    $form['filters']['specbee_country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#required' => TRUE,
      '#default_value' => $config->get('specbee_country'),
    ];
    $form['filters']['specbee_city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
      '#default_value' => $config->get('specbee_city'),
    ];
    $form['filters']['specbee_timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#required' => TRUE,
      '#default_value' => $config->get('specbee_timezone'),
      '#options' => [
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New York'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'),
        'Europe/London' => $this->t('Europe/London'),
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('specbee_timer.settings')
      ->set('specbee_country', $form_state->getValue('specbee_country'))
      ->set('specbee_city', $form_state->getValue('specbee_city'))
      ->set('specbee_timezone', $form_state->getValue('specbee_timezone'))
      ->save();

    $this->messenger->addMessage("TimeZone Configuration Saved");
  }

}
