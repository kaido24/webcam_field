<?php
namespace Drupal\webcam_field\Helper;

class WebcamHelper {
  function cache($field, $type, $id,$delta, $item) {
    $cid = "{$field}_{$type}_{$id}_{$delta}:"  . \Drupal::languageManager()
        ->getCurrentLanguage()
        ->getId();
    $camData = NULL;
    if ($cache = \Drupal::cache()
      ->get($cid)) {
      $camData = $cache->data;

      if($this->currentTimeMillis() >= $camData['time'] ) {
        $info = pathinfo($item->link);
        $data  = file_get_contents( $item->link);
        if ($data != false) {
          $imageSrc = 'data:image/'. $info['extension'] . ';base64,'. base64_encode($data);
          \Drupal::cache()
            ->set($cid, ['src' =>  $imageSrc, 'time' => $this->currentTimeMillis() + $item->refresh_rate]);
        }
      }
      return isset($imageSrc) ? $imageSrc : $camData['src'];
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
    return isset($imageSrc) ? $imageSrc : '';
  }
  function currentTimeMillis() {
    list($usec, $sec) = explode(" ", microtime());
    return round(((float)$usec + (float)$sec) * 1000);
  }
}
