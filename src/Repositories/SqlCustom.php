<?php

namespace Stephane888\Debug\Repositories;

/**
 * On a remarque un bug, sur drupal avec des requettes qui renvoit beaucoup de
 * données, le resultat est errornné.
 *
 * @bug voir 2408
 *
 * @useBy Drupal
 *
 * @author stephane
 *
 */
class SqlCustom {

  /**
   *
   * @param
   *        $entity_type
   */
  public static function loadEntityConfig($entity_type) {
    $connexion = \Drupal::database();
    $select = $connexion->select('config', 'cf');
  }

}