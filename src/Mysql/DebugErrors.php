<?php
namespace Stephane888\Debug\Mysql;

class DebugErrors {

  protected $codeError;

  protected $message;

  const codeDuplicate = 23000;

  const codeDefaultValue = 'HY000';

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
    switch ($this->codeError) {
      case self::codeDuplicate:
        return ErrorsMessages::$code_23000;
        break;
      case self::codeDefaultValue:
        return ErrorsMessages::$code_HY000;
        break;

      default:
        return ErrorsMessages::$code_empty;
        break;
    }
  }
}
//"SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '10-MCTCRECG' for key 'PRIMARY'"