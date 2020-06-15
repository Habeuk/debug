<?php
namespace Stephane888\Debug\Mysql;

class DebugErrors {

  protected $codeError;

  protected $message;

  const codeDuplicate = 23000;

  /**
   * Recherche une expression dans la chaine d'erreur.
   *
   * @param string $string
   * @return boolean // retourne true en cas d'erreur
   */
  public function analyseError($error)
  {
    if (\is_array($error) && ! empty($error['PHP_execution_error'])) {
      $this->codeError = $error['code'];
      $this->message = $error['message'];
      return true;
    }
    return false;
  }

  public function getCustomMessage()
  {
    if ($this->codeError == self::codeDuplicate) {
      return ErrorsMessages::$code_23000;
    } else {
      return ErrorsMessages::$code_empty;
    }
  }
}
//"SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '10-MCTCRECG' for key 'PRIMARY'"