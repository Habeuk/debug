<?php
namespace Stephane888\Debug;

use Exception;

class Utility {

  /**
   * Traite les message d'erreus lié à \Exception .
   * PHP 5
   *
   * @param Exception $e
   * @return boolean[]|NULL[]
   */
  public static function errorMessage(Exception $e)
  {
    return [
      'message' => $e->getMessage(),
      'code' => $e->getCode(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'previous' => $e->getPrevious(),
      'trace' => $e->getTrace(),
      'PHP_execution_error' => true
    ];
  }

  /**
   * Traite les message d'erreus lié à \Error.
   * PHP 5
   *
   * @param Exception $e
   * @return boolean[]|NULL[]
   */
  public static function errorError(\Error $e)
  {
    return [
      'message' => $e->getMessage(),
      'code' => $e->getCode(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'previous' => $e->getPrevious(),
      'trace' => $e->getTrace(),
      'PHP_execution_error' => true
    ];
  }
}