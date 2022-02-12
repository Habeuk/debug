<?php

namespace Stephane888\Debug;

use Kint\kint;
use kint\Utils;
use Kint\Renderer\RichRenderer;

class debugLog {
  
  /**
   * le path doi etre relatif.
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
  public static function logger($data, $filename = null, $auto = FALSE, $use = 'kint', $path_of_module = 'logs', $usePath = false) {
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
    }
    
    if (!file_exists($path_of_module)) {
      if (self::$debug)
        echo ('dossier en cour de creation dans :' . $path_of_module);
      if (mkdir($path_of_module, 0755, TRUE)) {
        chmod($path_of_module, 0755);
        if (self::$debug)
          echo (' Dossier OK ');
      }
      else {
        if (self::$debug)
          echo (' Echec creation dossier ');
      }
    }
    $filename = $path_of_module . '/' . $filename;
    
    if (!empty(self::$use)) {
      $use = self::$use;
    }
    
    // Traitement des données.
    if ($use == 'file') {
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
      
      fputs($monfichier, $result);
      fclose($monfichier);
      return true;
    }
    else {
      $filename = $filename . '.html';
      ob_start();
      DebugWbu::kint_bug($data, self::$max_depth);
      // DebugWbu::VarDumperBug($data);
      $result = ob_get_clean();
    }
    
    $monfichier = fopen($filename, 'w+');
    fputs($monfichier, $result);
    fclose($monfichier);
  }
  
  /**
   *
   * @deprecated
   * @param mixed $data
   * @param string $filename
   * @param boolean $auto
   * @param string $use
   * @param string $path_of_module
   * @param boolean $usePath
   * @return boolean
   */
  public static function logs($data, $filename = null, $auto = FALSE, $use = 'kint', $path_of_module = 'logs', $usePath = false) {
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
    }
    
    if (!file_exists($path_of_module . '/files-log')) {
      if (self::$debug)
        echo ('dossier en cour de creation dans :' . $path_of_module);
      if (mkdir($path_of_module . '/files-log', 0755, TRUE)) {
        chmod($path_of_module . '/files-log', 0755);
        if (self::$debug)
          echo (' Dossier OK ');
      }
      else {
        if (self::$debug)
          echo (' Echec creation dossier ');
      }
    }
    $filename = $path_of_module . '/files-log/' . $filename;
    
    if (!empty(self::$use)) {
      $use = self::$use;
    }
    
    // Traitement des données.
    if ($use == 'file') {
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
      
      fputs($monfichier, $result);
      fclose($monfichier);
      return true;
    }
    else {
      $filename = $filename . '.html';
      ob_start();
      DebugWbu::kint_bug($data, self::$max_depth);
      // DebugWbu::VarDumperBug($data);
      $result = ob_get_clean();
    }
    
    $monfichier = fopen($filename, 'w+');
    fputs($monfichier, $result);
    fclose($monfichier);
  }
  
  public static function kintDebugDrupal($data, $filename = 'debug', $auto = false, $path_of_module = null) {
    if (empty($path_of_module)) {
      if (self::$themeName) {
        $path_of_module = DRUPAL_ROOT . '/' . \drupal_get_path('theme', self::$themeName);
      }
      else {
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName);
      }
    }
    $use = 'kint';
    self::logs($data, $filename, $auto, $use, $path_of_module);
  }
  
  public static function SaveLogsDrupal($data, $filename = 'debug', $path_of_module = null) {
    if (empty($path_of_module)) {
      if (self::$themeName) {
        $path_of_module = DRUPAL_ROOT . '/' . \drupal_get_path('theme', self::$themeName);
      }
      else {
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        $path_of_module = DRUPAL_ROOT . '/' . drupal_get_path('theme', $defaultThemeName);
      }
    }
    $use = 'log';
    $auto = false;
    self::logs($data, $filename, $auto, $use, $path_of_module);
  }
  
  public static function saveLogs($data, $filename = 'debug', $path_of_module = 'logs') {
    $use = 'log';
    $auto = false;
    self::logs($data, $filename, $auto, $use, $path_of_module);
  }
  
  public static function saveJson(array $data, $filename = 'debug', $path_of_module = 'logs') {
    $use = 'json';
    $auto = false;
    $data = \json_encode($data);
    self::logs($data, $filename, $auto, $use, $path_of_module);
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