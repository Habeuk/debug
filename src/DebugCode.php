<?php

namespace Stephane888\Debug;

use LogicException;

/**
 *
 * @author stephane
 * @deprecated remove before 2x, because name "DebugCode" is not expressive or
 *             create confusion. use
 *             ExceptionDebug
 *            
 */
class DebugCode extends LogicException implements \Throwable {
  protected $content;
  
  /**
   *
   * @param string $message
   * @param int $code
   * @param mixed $previous
   * @param mixed $content
   */
  function __construct($message = null, $content = null, $code = null, $previous = null) {
    $this->content = $content;
    parent::__construct($message, $code, $previous);
  }
  
  function getContentToDebug() {
    return $this->content;
  }
  
  function setContentToDebug($dbg) {
    $this->content = $dbg;
  }
  
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