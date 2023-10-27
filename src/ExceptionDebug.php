<?php

namespace Stephane888\Debug;

use LogicException;

/**
 * Permet de sauvegarder le contenus de la variables $dbg dans les messages de
 * debocage par defaut.
 *
 * @author stephane
 *        
 */
class ExceptionDebug extends LogicException implements \Throwable {
  protected $dbg;
  
  /**
   * L'ide est de sauvegarder les informations de debug ($dbg) dans le fichier
   * de log par defaut.
   *
   * @param string $message
   * @param int $code
   *        la veleur doit etre choisie avec soing au risque d'avoir de mauvaise
   *        supprise dans le message.$this
   *        419-420 Unassigned
   *        432-450 Unassigned
   *        452-499 Unassigned
   * @see https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
   *
   * @param mixed $previous
   * @param mixed $dbg
   */
  function __construct($message = null, $dbg = null, $code = 432, $previous = null) {
    $this->dbg = $dbg;
    if ($dbg)
      $message = $message . ' || @debug ' . json_encode($dbg);
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
  
  function getErrorCode() {
    return $this->getCode();
  }
  
  /**
   *
   * @param string $message
   * @param mixed $dbg
   * @return \Stephane888\Debug\ExceptionDebug
   */
  static public function exception($message, $dbg = null) {
    return new self($message, $dbg);
  }
  
}