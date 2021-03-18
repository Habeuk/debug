<?php
namespace Stephane888\Debug;

// use Symfony\Component\HttpKernel\KernelInterface;
class SymfonyDebug extends debugLog {

  public $kernel;

  /**
   * //
   */
  function __construct()
  {
    // $this->kernel = $kernel;
  }

  /**
   * La valeur de debugLog::$path doit etre definit.
   *
   * @param mixed $data
   * @param string $filename
   * @param string $path_of_module
   */
  public static function saveLogs($data, $filename = 'debug', $path_of_module = '')
  {
    // $webPath = self::$kernel->getProjectDir(); // self::$kernel->getProjectDir();
    // dump($webPath);
    // debugLog::$path = "$webPath/var";
    $use = 'log';
    $auto = false;
    self::$PositionAddLogAfter = false;
    self::logs($data, $filename, $auto, $use);
  }
}