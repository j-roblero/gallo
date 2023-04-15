<?php

namespace Drupal\gp_layout_preview\Form;

use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Module settings form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The entity type bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandler
   */
  protected $themeHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeBundleInfoInterface $entity_type_bundle_info, ThemeHandlerInterface $theme_handler) {
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.bundle.info'),
      $container->get('theme_handler'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gp_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['gp_layout_preview.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('gp_layout_preview.settings');
    $default_values = $config->get('components.paragraph_list') ?? [];
    $paragraph_types = $this->entityTypeBundleInfo->getBundleInfo('paragraph');
    $bundles = [];

    foreach ($paragraph_types as $key => $type) {
      $bundles['paragraph__' . $key] = $type['label'];
    }

    $form['paragraph_list'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Paragraphs to preview:'),
      '#options' => $bundles,
      '#default_value' => array_keys($default_values),
      '#description' => $this->t('Paragraph types to be used as Layout Paragraphs components.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('gp_layout_preview.settings');
    $data = $form_state->getValue('paragraph_list');
    $formatted_data = [];
    $theme_path = $this->themeHandler
      ->getTheme($this->themeHandler->getDefault())
      ->getPath();

    foreach ($data as $key => $value) {
      if (!$value) {
        continue;
      }

      $formatted_data[$key] = [
        'template' => str_replace('_', '-', $key),
        'base hook' => 'paragraph',
        'path' => $theme_path . '/templates/paragraph',
      ];
    }

    $config->set('components.paragraph_list', $formatted_data);
    $config->save();

    return parent::submitForm($form, $form_state);
  }

}
