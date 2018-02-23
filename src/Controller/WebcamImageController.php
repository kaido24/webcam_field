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
    $cid = "{$field_name}_{$entity_type}_{$entity_id}_{$delta}:"  . \Drupal::languageManager()
        ->getCurrentLanguage()
        ->getId();
    $camData = NULL;
    if ($cache = \Drupal::cache()
      ->get($cid)) {
      $camData = $cache->data;
      $response = new HtmlResponse();
      $response->setContent($camData['src']);

      if($this->currentTimeMillis() >= $camData['time'] ) {
        ksm($camData['time']);
        $info = pathinfo($item->link);
        $data  = file_get_contents( $item->link);
        if ($data != false) {
          $imageSrc = 'data:image/'. $info['extension'] . ';base64,'. base64_encode($data);
          \Drupal::cache()
            ->set($cid, ['src' =>  $imageSrc, 'time' => $this->currentTimeMillis() + $item->refresh_rat]);
        }
      }
      return $response;
    }
    else {
      $info = pathinfo($item->link);
      $data  = file_get_contents( $item->link);
      if ($data != false) {
        $imageSrc = 'data:image/' . $info['extension'] . ';base64,' . base64_encode($data);
        \Drupal::cache()
          ->set($cid, ['src' => $imageSrc, 'time' => $this->currentTimeMillis() + $item->refresh_rate]);
      }
    }
    $response = new HtmlResponse();
    $response->setContent($camData['src']);
    return $response;
  }

  function currentTimeMillis() {
    list($usec, $sec) = explode(" ", microtime());
    return round(((float)$usec + (float)$sec) * 1000);
  }
}
