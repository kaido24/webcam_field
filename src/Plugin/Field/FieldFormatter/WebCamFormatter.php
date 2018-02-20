<?php
namespace Drupal\webcam_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
/**
 * Plugin implementation of the 'web_cam_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "web_cam_formatter",
 *   label = @Translation("Webcam image"),
 *   field_types = {
 *     "webcam_field_type"
 *   }
 * )
 */
class WebCamFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $user = \Drupal::currentUser();
    if ($items[0] === NULL || !$user->hasPermission('view webcam')) {
      return [];
    }
    $field_info = $items[0]->getParent();
    $entity = $field_info->getEntity();
    $id = $entity->id();
    $field_name = $field_info->getName();
    $type = $entity->getEntityTypeId();
    //ksm($type);


    foreach ($items as $delta => $item) {
      $url = Url::fromRoute('webcam_field.update', array(
        'entity_type' => $type,
        'entity_id' => $id,
        'field_name' => $field_name,
        'delta' => $delta,
      ));

      // return $data;
      $htmlid = 'webcam-'. $type . '-'. $field_name. '-' .$delta;
      $data = '<div id="' . $htmlid .
        # '" data-entity--id="' . $id .
        #  '" data-entity--type="'. $type .
        #   '" data-field--name="'. $field_name .
        #   '" data-field--delta="' . $delta .
        '" data-timeout="' . $item->refresh_rate .
        '" data-url="' . $url->tostring() .
        '" class="webcam-content"><div class="webcam-image"></div></div>';
      $elements[$delta] = ['#markup' => $data];
    }
    $elements['#attached']['library'][] = 'webcam_field/webcam';

    return $elements;
  }


}
