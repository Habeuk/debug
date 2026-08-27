<?php
namespace Stephane888\Debug\Mysql;

class DebugErrors {

  protected int|string $codeError = 400;

  protected string $message = '';

  const codeDuplicate = 23000;

  const codeDefaultValue = 'HY000';

  /**
   * Recherche une expression dans la chaine d'erreur.
   *
   * @param array<mixed> $error
   * @return bool
   */
  public function analyseError(array $error): bool {
    if (isset($error['PHP_execution_error']) && $error['PHP_execution_error'] === true) {
      $this->codeError = $error['code'];
      $this->message = $error['message'];
      return true;
    }
    return false;
  }

  public function getCustomMessage(): string|int {
    switch ($this->codeError) {
      case self::codeDuplicate:
        return ErrorsMessages::$code_23000;
      case self::codeDefaultValue:
        return ErrorsMessages::$code_HY000;
      default:
        return ErrorsMessages::$code_empty . '. Mysql code : ' . $this->codeError;
    }
  }
}