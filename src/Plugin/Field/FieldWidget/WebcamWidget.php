<?php
namespace Drupal\webcam_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'webcam_widget' widget.
 *
 * @FieldWidget(
 *   id = "webcam_widget",
 *   label = @Translation("Webcam info"),
 *   field_types = {
 *     "webcam_field_type"
 *   }
 * )
 */
class WebcamWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['link'] = [
      '#title' => $this->t('Webcam link'),
      '#description' => $this->t('If password is required for ftp or http basic auth then use ftp or http(s)://username:password@host/folder1/folder/2/file.jpg'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->link) ? $items[$delta]->link : NULL,
      '#size' => 50,
    ];
    $element['refresh_rate'] = [
      '#title' => $this->t('Refresh rate'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->refresh_rate) ? $items[$delta]->refresh_rate : NULL,
      '#size' => 50,
    ];
    return $element;
  }
}
