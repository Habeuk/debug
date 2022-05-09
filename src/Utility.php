<?php

namespace Stephane888\Debug;

use Exception;

class Utility {
  
  /**
   * Traite les message d'erreus lié à \Exception .
   * PHP 5
   *
   * @param Exception $e
   * @param Number $nbr_trace
   *        pour limiter le nombre d'erreur à afficher.
   * @return boolean[]|NULL[]
   */
  public static function errorMessage(Exception $e, int $nbr_trace = 3) {
    $er = [
      'message' => $e->getMessage(),
      'code' => $e->getCode(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'previous' => $e->getPrevious(),
      'trace' => array_slice($e->getTrace(), 0, 3),
      'PHP_execution_error' => true
    ];
    if ($nbr_trace)
      $er['trace'] = array_slice($e->getTrace(), 0, $nbr_trace);
    else
      $er['trace'] = $e->getTrace();
    return $er;
  }
  
  /**
   * Traite les message d'erreus lié à \Error.
   * PHP 5
   *
   * @param Exception $e
   * @param Number $nbr_trace
   *        pour limiter le nombre d'erreur à afficher.
   * @return boolean[]|NULL[]
   */
  public static function errorError(\Error $e, int $nbr_trace = 3) {
    $er = [
      'message' => $e->getMessage(),
      'code' => $e->getCode(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'previous' => $e->getPrevious(),
      'trace' => array_slice($e->getTrace(), 0, 3),
      'PHP_execution_error' => true
    ];
    if ($nbr_trace)
      $er['trace'] = array_slice($e->getTrace(), 0, $nbr_trace);
    else
      $er['trace'] = $e->getTrace();
    return $er;
  }
  
  /**
   * Traite les message d'erreus lié à \Error.
   * PHP 5
   *
   * @param Exception $e
   * @param Number $nbr_trace
   *        pour limiter le nombre d'erreur à afficher.
   * @return boolean[]|NULL[]
   */
  public static function errorAll(\Throwable $e, int $nbr_trace = 3) {
    $er = [
      'message' => $e->getMessage(),
      'code' => $e->getCode(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'previous' => $e->getPrevious(),
      'trace' => array_slice($e->getTrace(), 0, 3),
      'PHP_execution_error' => true
    ];
    if ($nbr_trace)
      $er['trace'] = array_slice($e->getTrace(), 0, $nbr_trace);
    else
      $er['trace'] = $e->getTrace();
    return $er;
  }
  
}