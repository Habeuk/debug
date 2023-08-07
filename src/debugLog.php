<?php

namespace Stephane888\Debug;

use Kint\kint;
use kint\Utils;
use Kint\Renderer\RichRenderer;
use Drupal\views\Plugin\views\field\Boolean;

class debugLog {
  
  /**
   * le path doit etre relatif.
   *
   * @var string
   */
  public static $path = null;
  
  /**
   * default value 3
   *
   * @var integer
   */
  public static $max_depth = 3;
  public static $auto = false;
  public static $use = null;
  public static $forcePath = false;
  public static $PositionAddLogAfter = true;
  public static $masterFileName = null;
  public static $themeName = null;
  public static $debug = true;
  
  /**
   * Debug php files or save value on file.
   *
   * @param mixed $data
   * @param string $filename
   * @param string $use
   * @param string $path_of_module
   * @param boolean $auto
   *        genere un code aleatoire pour chaque fichier.
   * @param boolean $usePath
   *        true on utilise le chemain definie dans le path.
   */
  public static function logger($data, $filename = null, $auto = FALSE, $use = 'kint', string $path_of_module = 'logs', $usePath = false) {
    if (!$filename) {
      $filename = 'debug';
      if (self::$masterFileName) {
        $filename = self::$masterFileName;
      }
    }
    
    if ($auto || self::$auto) {
      $filename = $filename . rand(1, 999);
    }
    if (!empty(self::$path)) {
      $path_of_module = self::$path;
    }
    if (defined('FULLROOT_WBU') && !self::$forcePath && !$usePath) {
      $path_of_module = FULLROOT_WBU . '/' . $path_of_module;
    }
    elseif (!$usePath) {
      $path_of_module = '/' . $path_of_module;
      $path_of_module = str_replace("//", "/", $path_of_module);
    }
    
    if (!file_exists($path_of_module)) {
      
      if (self::$debug)
        echo (' Dossier en cour de creation dans :' . $path_of_module);
      try {
        $test_create = mkdir($path_of_module, 0755, TRUE);
      }
      catch (\Exception $e) {
        return;
      }
      if ($test_create) {
        chmod($path_of_module, 0755);
        if (self::$debug)
          echo (' Dossier OK ');
      }
      else {
        if (self::$debug)
          echo (' Echec creation dossier ');
        return;
      }
    }
    
    $filename = $path_of_module . '/' . $filename;
    if (!empty(self::$use)) {
      $use = self::$use;
    }
    // Traitement des données.
    if ($use == 'file') {
      if (is_array($data) || is_object($data)) {
        $data = json_encode($data);
      }
      $result = $data;
    } //
    elseif ($use == 'json') {
      $filename = $filename . '.json';
      $result = $data;
    } //
    elseif ($use == 'log') {
      if (is_array($data) || is_object($data)) {
        ob_start();
        print_r($data);
        $result = ob_get_clean();
      }
      else {
        $result = $data;
      }
      $logs = PHP_EOL . PHP_EOL . 'Date : ' . date("d/m/Y  H:i:s") . '' . PHP_EOL;
      $result = $logs . $result;
      if (self::$PositionAddLogAfter) {
        $monfichier = fopen($filename, "a+");
      }
      else {
        if (file_exists($filename)) {
          $result .= file_get_contents($filename);
        }
        $monfichier = fopen($filename, "w");
      }
      if ($result !== Null && $monfichier) {
        fputs($monfichier, $result);
        fclose($monfichier);
      }
      
      return true;
    }
    //
    elseif ($use == 'symfony') {
      $filename = $filename . '.html';
      $result = DebugWbu::Dumper3($data);
    }
    // use 'kint'
    else {
      $filename = $filename . '.html';
      ob_start();
      DebugWbu::kint_bug($data, self::$max_depth);
      // DebugWbu::VarDumperBug($data);
      $result = ob_get_clean();
    }
    //
    $monfichier = fopen($filename, 'w+');
    if ($monfichier) {
      if ($result !== Null && $monfichier) {
        fwrite($monfichier, $result);
        fclose($monfichier);
      }
    }
    else {
      echo " file not writable : " . $filename . '<br>';
    }
  }
  
  /**
   *
   * @param mixed $data
   * @param string $filename
   * @param Boolean $auto
   * @param string $path_of_module
   *        Un chemin relatif serra dans le theme ou un chemin absolute
   */
  public static function kintDebugDrupal($data, $filename = 'debug', $auto = false, string $path_of_module = 'logs') {
    if (empty($path_of_module)) {
      // si on est dans un environnement drupal, on renvoit cela dans le theme
      // encours.
      if (defined('DRUPAL_ROOT')) {
        if (self::$themeName) {
          $path_of_module = DRUPAL_ROOT . '/' . \drupal_get_path('theme', self::$themeName);
        }
        else {
          $defaultThemeName = \Drupal::config('system.theme')->get('default');
          $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName);
        }
      }
    }
    else {
      // si on est dans un environnement drupal, on renvoit cela dans le theme
      // encours.
      if (defined('DRUPAL_ROOT')) {
        if (self::$themeName) {
          $path_of_module = DRUPAL_ROOT . '/' . \drupal_get_path('theme', self::$themeName) . "/" . $path_of_module;
        }
        else {
          $defaultThemeName = \Drupal::config('system.theme')->get('default');
          $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName) . "/" . $path_of_module;
        }
      }
    }
    $use = 'kint';
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }
  
  /**
   * Methode de debugage inspirer de symfony.
   */
  public static function symfonyDebug($data, $filename = 'debug', $auto = false, string $path_of_module = 'logs') {
    if (empty($path_of_module)) {
      if (self::$themeName) {
        $path_of_module = DRUPAL_ROOT . '/' . \drupal_get_path('theme', self::$themeName);
      }
      else {
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName);
      }
    }
    $use = 'symfony';
    // $use = 'kint';
    $usePath = false;
    self::logger($data, $filename, $auto, $use, $path_of_module, $usePath);
  }
  
  public static function DebugDrupal($data, $filename = 'debug', $auto = false, string $path_of_module = 'logs', $use = 'log') {
    if (empty($path_of_module)) {
      if (self::$themeName) {
        $path_of_module = DRUPAL_ROOT . '/' . \drupal_get_path('theme', self::$themeName);
      }
      else {
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName);
      }
    }
    else {
      if ($path_of_module[0] != "/") {
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName) . "/" . $path_of_module;
      }
    }
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }
  
  public static function SaveLogsDrupal($data, $filename = 'debug', string $path_of_module = 'logs') {
    if (empty($path_of_module)) {
      if (self::$themeName) {
        $path_of_module = DRUPAL_ROOT . '/' . \drupal_get_path('theme', self::$themeName);
      }
      else {
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName);
      }
    }
    else {
      // si on est dans un environnement drupal, on renvoit cela dans le theme
      // encours.
      if (defined('DRUPAL_ROOT')) {
        if ($path_of_module[0] != "/") {
          $defaultThemeName = \Drupal::config('system.theme')->get('default');
          $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName) . "/" . $path_of_module;
        }
      }
    }
    $use = 'log';
    $auto = false;
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }
  
  public static function saveLogs($data, $filename = 'debug', string $path_of_module = 'logs') {
    $use = 'log';
    $auto = false;
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }
  
  public static function saveJson(array $data, $filename = 'debug', string $path_of_module = 'logs') {
    $use = 'json';
    $auto = false;
    $data = \json_encode($data);
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }
  
  public static function savexml($data, $filename = null, $auto = false) {
    if (!$filename) {
      $filename = 'debug';
    }
    if ($auto) {
      $filename = $filename . rand(1, 999);
    }
    $path_of_module = 'api/src/logs';
    $path_of_module = FULLROOT_WBU . '/' . $path_of_module;
    if (!file_exists($path_of_module . '/files-xml')) {
      if (self::$debug)
        echo ('dossier en cour de creation dans :' . $path_of_module);
      if (mkdir($path_of_module . '/files-log', $mode = '0755', $recursive = TRUE)) {
        if (self::$debug)
          echo (' Dossier OK ');
      }
      else {
        if (self::$debug)
          echo (' Echec creation dossier');
      }
    }
    
    $filename = $path_of_module . '/files-xml/' . $filename . '.xml';
    $monfichier = fopen($filename, 'w+');
    fputs($monfichier, $data);
    fclose($monfichier);
  }
  
}