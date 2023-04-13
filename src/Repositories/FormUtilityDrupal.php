<?php

namespace Stephane888\Debug\Repositories;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Render\Element;

/**
 * permet de recuperer la configuration d'un module qui a été surcharger par le
 * module domain.
 *
 * @useBy Drupal
 *
 * @author stephane
 *
 */
class FormUtilityDrupal {

  /**
   * Permet de convertir un formulaire d'entité en un formulaire simple.
   * Ceci est utilise pour faire du Step by step.
   */
  static public function ConvertEntityFormToSimpleForm(array $form, array &$formSimple, array $arrayParents = []) {
    // On retire les elements qui ne correspondent pas à un champs.
    $eltRemove = [
      "#process",
      "#after_build",
      "#build_id",
      "#cache",
      "#token",
      "#id",
      "#processed",
      "#submit",
      "#validate",
      "#method",
      "#after_build_done"
    ];
    if (isset($arrayParents))
      $formSimple['#array_parents'] = $arrayParents;
    foreach ($form as $key => $field) {
      // if ((!in_array($key, $eltRemove) && !str_contains($key, "action")) &&
      // !str_contains($key, "form_")) {
      if ((!str_contains($key, "#") && !str_contains($key, "action")) && !str_contains($key, "form_")) {
        if (!empty($arrayParents)) {
          $field['#parents'] = NestedArray::mergeDeepArray([
            $arrayParents,
            $field['#parents']
          ]);
          $field['#array_parents'] = $field['#parents'];
        }
        if (isset($field['#name']))
          unset($field['#name']);
        if (isset($field['#value']))
          unset($field['#value']);

        // Pour chaque champs, on doit verifier si on'a pas de sous champs:
        $childIds = Element::children($field);
        if (!empty($childIds)) {
          self::ConvertField($field, $childIds);
        }
        $formSimple[$key] = $field;
      }
    }
    // pour que #action ne soit plus obligatoire.
    if ($formSimple['#type'] == 'form')
      unset($formSimple['#type']);

    return $formSimple;
  }

  static protected function ConvertField(&$field, $childIds) {
    foreach ($childIds as $delta) {
      if (isset($field[$delta]['#name']))
        unset($field[$delta]['#name']);
      if (isset($field[$delta]['#value']))
        unset($field[$delta]['#value']);

      $field[$delta]['#parents'] = NestedArray::mergeDeepArray([
        $field['#parents'],
        [
          $delta
        ]
      ]);
      $field[$delta]['#array_parents'] = $field[$delta]['#parents'];
      $childIdsChild = Element::children($field[$delta]);
      if (!empty($childIdsChild)) {
        self::ConvertField($field[$delta], $childIdsChild);
      }
    }
  }

}