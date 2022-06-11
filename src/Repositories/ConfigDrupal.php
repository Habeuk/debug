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
   * Retourne la configuration surcharger par domain_config ou la configuration
   * par defaut.
   *
   * @param string $name
   * @return NULL
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