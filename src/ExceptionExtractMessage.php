<?php

namespace Stephane888\Debug;

use Exception;

/**
 *
 * @author stephane
 *        
 */
class ExceptionExtractMessage {
  
  /**
   * Traite les message d'erreus lié à \Exception .
   * PHP 5
   *
   * @param Exception $e
   * @param Number $nbr_trace
   *        pour limiter le nombre d'erreur à afficher.
   * @return boolean[]|NULL[]
   *
   */
  public static function errorMessage(Exception $e, int $nbr_trace = 7) {
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
  public static function errorError(\Error $e, int $nbr_trace = 7) {
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
   * @return [] contenant les informations liées à l'erreur.
   */
  public static function errorAll(\Throwable $e, int $nbr_trace = 7) {
    $er = [
      'message' => $e->getMessage(),
      'code' => $e->getCode(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'previous' => $e->getPrevious(),
      'trace' => array_slice($e->getTrace(), 0, $nbr_trace),
      'PHP_execution_error' => true
    ];
    return $er;
  }
  
  public static function errorAllToString(\Throwable $e, int $nbr_trace = 7) {
    $error = '';
    $error .= '<br>';
    $error .= $e->getMessage();
    $error .= '<br>';
    $error .= $e->getCode();
    $error .= '<br>';
    $error .= $e->getFile();
    $error .= '<br>';
    $error .= $e->getLine();
    $error .= '<br>';
    $error .= $e->getPrevious();
    foreach (array_slice($e->getTrace(), 0, $nbr_trace) as $value) {
      $error .= '<br>';
      if (!is_array($value) && !is_object($value))
        $error .= $value;
    }
    return $error;
  }
  
}