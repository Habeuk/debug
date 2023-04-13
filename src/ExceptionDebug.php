<?php

namespace Stephane888\Debug;

use LogicException;

/**
 *
 * @author stephane
 *        
 */
class ExceptionDebug extends LogicException implements \Throwable {
  protected $dbg;
  
  /**
   *
   * @param string $message
   * @param int $code
   * @param mixed $previous
   * @param mixed $dbg
   */
  function __construct($message = null, $dbg = null, $code = null, $previous = null) {
    $this->dbg = $dbg;
    parent::__construct($message, $code, $previous);
  }
  
  function getContentToDebug() {
    return $this->dbg;
  }
  
  function setContentToDebug($dbg) {
    $this->dbg = $dbg;
  }
  
  public function setErrors(array $errors) {
    $this->setContentToDebug($errors);
  }
  
  public function getErrors() {
    return $this->dbg;
  }
  
  /**
   *
   * @deprecated
   */
  function getError() {
    return $this->getMessage();
  }
  
  function getErrorCode() {
    if (empty($this->getCode()))
      return 431;
    return $this->getCode();
  }
  
  static public function exception($message, $dbg) {
    return new self($message, $dbg);
  }
  
}