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
   *        On s'inpire de des codes d'erreurs de monolog. @see
   *        https://github.com/Seldaek/monolog/blob/2.x/doc/01-usage.md#log-levels
   * @param mixed $previous
   * @param mixed $dbg
   */
  function __construct($message = null, $dbg = null, $code = 100, $previous = null) {
    $this->dbg = $dbg;
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
    if (empty($this->getCode()))
      return 431;
    return $this->getCode();
  }

  static public function exception($message, $dbg) {
    return new self($message, $dbg);
  }

}