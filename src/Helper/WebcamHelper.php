<?php
namespace Drupal\webcam_field\Helper;

use DrupalCodeGenerator\Command\Drupal_8\Test\Web;
use GuzzleHttp\Exception\GuzzleException;

class WebcamHelper {
  function requesturl($url, $timeout) {
    // The timeout is in seconds
    $client = new \GuzzleHttp\Client();

    try {
      $res = $client->request('GET', $url, ['timeout' => $timeout]);
    } catch (GuzzleException $e) {
      return false;
    }

    $status = $res->getStatusCode();

    if($status!=200){
      return false;
    }

    $body = $res->getBody();

    return $body;
  }
  function cache($field, $type, $id,$delta, $item) {
    // Returns false on failure
    $cid = "{$field}_{$type}_{$id}_{$delta}:"  . \Drupal::languageManager()
        ->getCurrentLanguage()
        ->getId();
    $camData = NULL;

    if ($cache = \Drupal::cache()
      ->get($cid)) {
      // It's in cache
      $camData = $cache->data;

      if($this->currentTimeMillis() >= $camData['time'] ) {
        // Cache expired
        $info = pathinfo($item->link);
        $data  = WebcamHelper::requesturl( $item->link, $item->refresh_rate/1000);

        if ($data != false) {
          $imageSrc = 'data:image/' . $info['extension'] . ';base64,' . base64_encode($data);
        }elseif($camData['src'] != false){
          // Failed to retrieve the image, but there is an old one in the cache
          $imageSrc = $camData['src'];
        }else{
          // Failed to retrieve the image, and there isn't one in the cache
          $imageSrc = false;
        }
        \Drupal::cache()
          ->set($cid, ['src' =>  $imageSrc, 'time' => $this->currentTimeMillis() + $item->refresh_rate]);
      }
      return isset($imageSrc) ? $imageSrc : $camData['src'];
    }
    else {
      // We don't have it in cache
      $info = pathinfo($item->link);
      $data  = WebcamHelper::requesturl( $item->link, $item->refresh_rate/1000);

      if ($data != false) {
        $imageSrc = 'data:image/' . $info['extension'] . ';base64,' . base64_encode($data);
      }else{
        $imageSrc = false;
      }

      \Drupal::cache()
        ->set($cid, ['src' =>  $imageSrc, 'time' => $this->currentTimeMillis() + $item->refresh_rate]);
    }
    return $imageSrc;
  }
  function currentTimeMillis() {
    list($usec, $sec) = explode(" ", microtime());
    return round(((float)$usec + (float)$sec) * 1000);
  }
}
