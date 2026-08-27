<?php
namespace Stephane888\Debug;

use Drupal\Core\Extension\ExtensionPathResolver;

class debugLog {

  /**
   * le path doit etre relatif.
   *
   * @var string
   */
  public static ?string $path = null;

  /**
   * default value 3
   *
   * @var integer
   */
  public static int $max_depth = 3;

  public static bool $auto = false;

  public static ?string $use = null;

  public static bool $forcePath = false;

  public static bool $PositionAddLogAfter = true;

  public static ?string $masterFileName = null;

  public static ?string $themeName = null;

  public static bool $debug = true;

  /**
   *
   * @phpstan-ignore-next-line
   */
  protected static ?ExtensionPathResolver $pathResolver = null;

  /**
   * Debug php files or save value on file.
   *
   * @param mixed $data
   * @param string $filename
   * @param string $use
   * @param string $path_of_module
   * @param boolean $auto genere un code aleatoire pour chaque fichier.
   * @param boolean $usePath true on utilise le chemain definie dans le path.
   */
  public static function logger(mixed $data, ?string $filename = null, bool $auto = FALSE, string $use = 'kint', string $path_of_module = 'logs',
    bool $usePath = false): void {
    if ($filename === null) {
      $filename = 'debug';
      if (self::$masterFileName !== null) {
        $filename = self::$masterFileName;
      }
    }

    if ($auto || self::$auto) {
      $filename = $filename . rand(1, 999);
    }
    if (self::$path !== null) {
      $path_of_module = self::$path;
    }
    if (defined('FULLROOT_WBU') && ! self::$forcePath && ! $usePath) {
      $path_of_module = FULLROOT_WBU . '/' . $path_of_module;
    }
    elseif (! $usePath) {
      $path_of_module = '/' . $path_of_module;
      $path_of_module = str_replace("//", "/", $path_of_module);
    }

    if (! file_exists($path_of_module)) {

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
    if (self::$use !== null) {
      $use = self::$use;
    }
    // Traitement des données.
    if ($use === 'file') {
      if (is_array($data) || is_object($data)) {
        $data = json_encode($data);
      }
      $result = $data;
    } //
    elseif ($use === 'json') {
      $filename = $filename . '.json';
      $result = $data;
    } //
    elseif ($use === 'log') {
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
      if ($monfichier !== false) {
        fputs($monfichier, $result);
        fclose($monfichier);
      }
    }
    //
    elseif ($use === 'symfony') {
      $filename = $filename . '.html';
      $result = DebugWbu::Dumper3($data);
    }
    elseif ($use === 'trace') {
      $filename = $filename . '.html';
      ob_start();
      DebugWbu::trace(self::$max_depth);
      $result = ob_get_clean();
    }
    // use 'kint'
    else {
      $filename = $filename . '.html';
      ob_start();
      DebugWbu::kint_bug($data, self::$max_depth);
      $result = ob_get_clean();
    }
    //
    $monfichier = fopen($filename, 'w+');
    if ($monfichier !== false) {
      if ($result !== false) {
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
   * @param string $path_of_module Un chemin relatif serra dans le theme ou un chemin absolute
   */
  public static function kintDebugDrupal(mixed $data, string $filename = 'debug', bool $auto = false, ?string $path_of_module = 'logs'): void {
    if ($path_of_module === null) {
      // si on est dans un environnement drupal, on renvoit cela dans le theme
      // encours.
      if (defined('DRUPAL_ROOT')) {
        if (self::$themeName !== null) {
          $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', self::$themeName);
        }
        else {
          /**
           *
           * @phpstan-ignore-next-line
           */
          $defaultThemeName = \Drupal::config('system.theme')->get('default');
          $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName);
        }
      }
    }
    else {
      // si on est dans un environnement drupal, on renvoit cela dans le theme
      // encours.
      if (defined('DRUPAL_ROOT')) {
        if (self::$themeName !== null) {
          $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', self::$themeName) . "/" . $path_of_module;
        }
        else {
          /**
           *
           * @phpstan-ignore-next-line
           */
          $defaultThemeName = \Drupal::config('system.theme')->get('default');
          $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName) . "/" . $path_of_module;
        }
      }
    }
    $use = 'kint';
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }

  /**
   * Methode de debogage inspirer de symfony.
   * Cette approche n'affiche pas le bloc des methodes, classes en relations
   * avec l'object.
   */
  public static function symfonyDebug(mixed $data, string $filename = 'debug', bool $auto = false, string $path_of_module = 'logs'): void {
    if (defined('DRUPAL_ROOT')) {
      if (self::$themeName !== null) {
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', self::$themeName) . "/" . $path_of_module;
      }
      else {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName) . "/" . $path_of_module;
      }
    }
    elseif (self::get_kernel() !== false) {
      $path_of_module = self::get_kernel()->getProjectDir() . '/' . $path_of_module;
    }
    $use = 'symfony';
    // $use = 'kint';
    $usePath = false;
    self::logger($data, $filename, $auto, $use, $path_of_module, $usePath);
  }

  public static function symfonyKintDebug(mixed $data, string $filename = 'debug', bool $auto = false, string $path_of_module = 'logs'): void {
    if (self::get_kernel() !== false) {
      $path_of_module = self::get_kernel()->getProjectDir() . '/' . $path_of_module;
    }
    $use = 'kint';
    // $use = 'kint';
    $usePath = false;
    self::logger($data, $filename, $auto, $use, $path_of_module, $usePath);
  }

  /**
   * Recuperer le Kernel de symfony
   *
   * @return \App\Kernel
   */
  public static function get_kernel(): \App\Kernel|false {
    static $kernel;
    if (class_exists(\App\Kernel::class))
      if (! $kernel) {
        $kernel = new \App\Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
      }
    return $kernel ?? false;
  }

  /**
   * attention utilise beacoup de memoire.
   *
   * @param mixed $data
   * @param string $filename
   * @param boolean $auto
   * @param string $path_of_module
   * @param string $use
   */
  public static function DebugDrupal(mixed $data, string $filename = 'debug', bool $auto = false, ?string $path_of_module = 'logs', string $use = 'log'): void {
    if ($path_of_module === null) {
      if (self::$themeName === null) {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', self::$themeName);
      }
      else {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName);
      }
    }
    else {
      if ($path_of_module[0] !== "/") {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName) . "/" . $path_of_module;
      }
    }
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }

  public static function TraceDrupal(string $filename = 'trace', bool $auto = false, ?string $path_of_module = 'logs'): void {
    if ($path_of_module === null) {
      if (self::$themeName !== null) {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', self::$themeName);
      }
      else {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName);
      }
    }
    else {
      if ($path_of_module[0] !== "/") {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName) . "/" . $path_of_module;
      }
    }
    $use = 'trace';
    self::logger([], $filename, $auto, $use, $path_of_module);
  }

  /**
   *
   * @param mixed $data
   * @param string $filename
   * @param string $path_of_module
   */
  public static function SaveLogsDrupal(mixed $data, string $filename = 'debug', ?string $path_of_module = 'logs'): void {
    if ($path_of_module === null) {
      if (self::$themeName !== null) {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', self::$themeName);
      }
      else {
        /**
         *
         * @phpstan-ignore-next-line
         */
        $defaultThemeName = \Drupal::config('system.theme')->get('default');
        /**
         *
         * @phpstan-ignore-next-line
         */
        $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName);
      }
    }
    else {
      // Si on est dans un environnement drupal, on renvoit cela dans le theme
      // encours.
      if (defined('DRUPAL_ROOT')) {
        if ($path_of_module[0] !== "/") {
          /**
           *
           * @phpstan-ignore-next-line
           */
          $defaultThemeName = \Drupal::config('system.theme')->get('default');
          $path_of_module = DRUPAL_ROOT . '/' . self::getPath('theme', $defaultThemeName) . "/" . $path_of_module;
        }
      }
    }
    $use = 'log';
    $auto = false;
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }

  public static function saveLogs(mixed $data, string $filename = 'debug', string $path_of_module = 'logs'): void {
    $use = 'log';
    $auto = false;
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }

  /**
   *
   * @param array<mixed> $data
   * @param string $filename
   * @param string $path_of_module
   */
  public static function saveJson(array $data, string $filename = 'debug', string $path_of_module = 'logs'): void {
    $use = 'json';
    $auto = false;
    $data = \json_encode($data);
    self::logger($data, $filename, $auto, $use, $path_of_module);
  }

  public static function savexml(mixed $data, ?string $filename = null, bool $auto = false): void {
    if ($filename === null) {
      $filename = 'debug';
    }
    if ($auto) {
      $filename = $filename . rand(1, 999);
    }
    $path_of_module = 'api/src/logs';
    if (defined('FULLROOT_WBU'))
      $path_of_module = FULLROOT_WBU . '/' . $path_of_module;
    if (! file_exists($path_of_module . '/files-xml')) {
      if (self::$debug)
        echo ('dossier en cour de creation dans :' . $path_of_module);
      $mode = 0755;
      $recursive = TRUE;
      if (mkdir($path_of_module . '/files-log', $mode, $recursive)) {
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
    if ($monfichier !== false) {
      fputs($monfichier, $data);
      fclose($monfichier);
    }
  }

  /**
   *
   * @phpstan-ignore-next-line
   */
  public static function getPath(string $type, string $name): ExtensionPathResolver {
    if (self::$pathResolver === null) {
      /**
       *
       * @phpstan-ignore-next-line
       */
      self::$pathResolver = \Drupal::service('extension.path.resolver');
    }
    /**
     *
     * @phpstan-ignore-next-line
     */
    return self::$pathResolver->getPath($type, $name);
  }
}