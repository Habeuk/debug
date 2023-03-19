<?php

namespace Stephane888\Debug\Repositories;

/**
 * permet de recuperer la configuration d'un module qui a été surcharger par le
 * module domain.
 *
 * @useBy Drupal
 *
 * @author stephane
 *        
 */
class ConfigDrupal {
  
  /**
   * Permet de rechercher les configuration en relation avec un terme.
   * Par defaut on fait la recherche sur la colonne "name", mais cela peut etre
   * necessaire de l'effectuer aussi dans data. Mais cdans ce cas il faut faire
   * attention au risque de tout casser.
   *
   *
   * @param string $name
   */
  static function searchConfigByWord(string $word, $full = false) {
    /**
     *
     * @var \Drupal\Core\Database\Connection $database
     * @see https://www.drupal.org/docs/8/api/database-api/dynamic-queries/introduction-to-dynamic-queries
     */
    $database = \Drupal::database();
    $configTable = $database->select('config', 'cf');
    $configTable->fields('cf', []);
    if ($full) {
      $orGroupe = $configTable->orConditionGroup();
      $orGroupe->condition('name', '%' . $database->escapeLike($word) . '%', "LIKE");
      $orGroupe->condition('data', '%' . $database->escapeLike($word) . '%', "LIKE");
      $configTable->condition($orGroupe);
    }
    else {
      $configTable->condition('name', '%' . $database->escapeLike($word) . '%', "LIKE");
    }
    // $configTable->condition('name', $word);
    return $configTable->execute()->fetchAll(\PDO::FETCH_ASSOC);
  }
  
  /**
   * Retourne la configuration surcharger par domain_config ou la configuration
   * par defaut.
   *
   * @param string $name
   * @return Array of config
   */
  static function config(string $name) {
    $conf = self::overrideConfig([
      $name
    ]);
    if (!isset($conf[$name])) {
      return self::defaultConfig($name);
    }
    return $conf[$name];
  }
  
  /**
   * Permet de recuperer la config du theme actif, ou de celui definit en
   * paramettre.
   */
  static function getSettingsTheme($theme_name = null) {
    if (!$theme_name) {
      $conf = self::config('system.theme');
      $theme_name = $conf['default'];
    }
    return self::config($theme_name . '.settings');
  }
  
  static protected function overrideConfig(array $names) {
    if (\Drupal::getContainer()->has('domain_config.overrider')) {
      $DomainConfigOverrider = \Drupal::service('domain_config.overrider');
      return $DomainConfigOverrider->loadOverrides($names);
    }
    return null;
  }
  
  static protected function defaultConfig($name) {
    $configs = \Drupal::config($name);
    return $configs->getRawData();
  }
  
}