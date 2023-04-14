<?php

namespace Drupal\gp_layout_preview\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

/**
 * Redirects to the node edit form.
 */
class LayoutPreviewController extends ControllerBase {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->entityTypeManager = $this->entityTypeManager();
    $this->formBuilder = $this->formBuilder();
  }

  /**
   * Displays the node edit form.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   *
   * @return array|\Drupal\Core\Access\AccessResultForbidden
   *   The rendered node edit form or an access forbidden response.
   */
  public function getForm(NodeInterface $node) {
    $form = $this->entityTypeManager
      ->getFormObject('node', 'default')
      ->setEntity($node);

    return $this->formBuilder->getForm($form);
  }

  /**
   * Checks if the node has a layout form.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   */
  public function access(NodeInterface $node) {
    $isPage = $node->bundle() === 'page';

    return $isPage ? AccessResult::allowed() : AccessResult::forbidden();
  }

}
