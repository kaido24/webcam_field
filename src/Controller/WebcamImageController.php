<?php

namespace Drupal\webcam_field\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\HtmlResponse;

/**
 * Class WebcamImageController.
 */
class WebcamImageController extends ControllerBase {
  function update( $entity_type, $entity_id, $field_name, $delta) {
    $entityManager = \Drupal::entityTypeManager();
    $entity = $entityManager->getStorage($entity_type)->load($entity_id);
    $item = $entity->get($field_name)->get($delta);
    $info = pathinfo($item->link);
    $data  = file_get_contents( $item->link);
    $imageSrc = 'data:image/'. $info['extension'] . ';base64,'. base64_encode($data);
    $response = new HtmlResponse();
    $response->setContent($imageSrc);
    return $response;
  }
}
