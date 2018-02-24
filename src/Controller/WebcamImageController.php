<?php

namespace Drupal\webcam_field\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\HtmlResponse;
use Drupal\webcam_field\Helper\WebcamHelper;
/**
 * Class WebcamImageController.
 */
class WebcamImageController extends ControllerBase {
  function update( $entity_type, $entity_id, $field_name, $delta) {
    $entityManager = \Drupal::entityTypeManager();
    $entity = $entityManager->getStorage($entity_type)->load($entity_id);
    $item = $entity->get($field_name)->get($delta);
    $data = new WebcamHelper();
    $imgSrc = $data->cache($entity_type, $entity_id, $field_name, $delta, $item);
    $response = new HtmlResponse();
    $response->setContent($imgSrc);
    return $response;
  }
}
