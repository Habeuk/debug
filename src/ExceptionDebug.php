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

  protected mixed $dbg;

  /**
   * L'ide est de sauvegarder les informations de debug ($dbg) dans le fichier
   * de log par defaut.
   *
   * @param string $message
   * @param int $code la veleur doit etre choisie avec soing au risque d'avoir de mauvaise
   *        supprise dans le message.$this
   *        419-420 Unassigned
   *        432-450 Unassigned
   *        452-499 Unassigned
   * @see https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
   *
   * @param mixed $previous
   * @param mixed $dbg
   */
  function __construct($message = null, mixed $dbg = null, $code = 432, $previous = null) {
    $this->dbg = $dbg;
    if (is_array($dbg) || is_object($dbg))
      $message = $message . ' || @debug ' . json_encode($dbg);
    else $message = $message . ' || @debug ' . $dbg;
    parent::__construct($message, $code, $previous);
  }

  function getContentToDebug(): mixed {
    return $this->dbg;
  }

  function setContentToDebug(mixed $dbg): void {
    $this->dbg = $dbg;
  }

  /**
   *
   * @param array<mixed> $errors
   */
  public function setErrors(array $errors): void {
    $this->setContentToDebug($errors);
  }

  public function getErrors(): mixed {
    return $this->dbg;
  }

  function getErrorCode(): int {
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
