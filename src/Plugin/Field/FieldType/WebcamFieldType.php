<?php
namespace Drupal\webcam_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'webcam_field_type' field type.
 *
 * @FieldType(
 *   id = "webcam_field_type",
 *   label = @Translation("Webcam field link"),
 *   description = @Translation("Webcam field"),
 *   default_widget = "webcam_widget",
 *   default_formatter = "web_cam_formatter"
 * )
 */
class WebcamFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties['link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Link host'))
      ->setRequired(TRUE);
    $properties['refresh_rate'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Refresh rate'))
      ->setRequired(TRUE);
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'link' => [
          'type' => 'varchar',
          'length' => 255,
        ],
        'refresh_rate' => [
          'type' =>  'varchar',
          'length' => 255,
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();
    return $constraints;
  }
  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $elements = [];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $link = $this->get('link')->getValue();
    $refresh_rate = $this->get('refresh_rate')->getValue();
    if (($link === NULL || $link === '') || ($refresh_rate === NULL || $refresh_rate === '')){
      return true;
    }
    return false;
  }

}
