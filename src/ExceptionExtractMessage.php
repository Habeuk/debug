<?php
declare(strict_types = 1);
namespace Stephane888\Debug;

final class ExceptionExtractMessage {

  /**
   * Compatibilité historique : extrait les détails d'une exception ou erreur PHP.
   *
   * @return array{ message: string,
   *         code: int|string,
   *         file: string,
   *         line: int,
   *         previous: \Throwable|null,
   *         trace: array<int, array<string, mixed>>,
   *         PHP_execution_error: bool
   *         }
   */
  public static function errorMessage(\Throwable $e, int $nbrTrace = 7): array {
    return self::errorAll($e, $nbrTrace);
  }

  /**
   * Compatibilité historique : extrait les détails d'une erreur PHP.
   *
   * @return array{ message: string,
   *         code: int|string,
   *         file: string,
   *         line: int,
   *         previous: \Throwable|null,
   *         trace: array<int, array<string, mixed>>,
   *         PHP_execution_error: bool
   *         }
   */
  public static function errorError(\Throwable $e, int $nbrTrace = 7): array {
    return self::errorAll($e, $nbrTrace);
  }

  /**
   * Méthode principale : extrait les détails d'un Throwable.
   *
   * @return array{ message: string,
   *         code: int|string,
   *         file: string,
   *         line: int,
   *         previous: \Throwable|null,
   *         trace: array<int, array<string, mixed>>,
   *         PHP_execution_error: bool
   *         }
   */
  public static function errorAll(\Throwable $e, int $nbrTrace = 7): array {
    return [
      'message' => $e->getMessage(),
      'code' => $e->getCode(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'previous' => $e->getPrevious(),
      'trace' => self::extractTrace($e, $nbrTrace),
      'PHP_execution_error' => true
    ];
  }

  public static function errorAllToString(\Throwable $e, int $nbrTrace = 7): string {
    $error = self::errorAll($e, $nbrTrace);

    $lines = [
      $error['message'],
      (string) $error['code'],
      $error['file'],
      (string) $error['line']
    ];

    if ($error['previous'] instanceof \Throwable) {
      $lines[] = $error['previous']->getMessage();
    }

    foreach ($error['trace'] as $trace) {
      $lines[] = self::traceToString($trace);
    }

    return implode('<br>', array_filter($lines, static fn (string $line): bool => $line !== ''));
  }

  /**
   *
   * @return array<int, array<string, mixed>>
   */
  private static function extractTrace(\Throwable $e, int $nbrTrace): array {
    if ($nbrTrace <= 0) {
      return $e->getTrace();
    }

    return array_slice($e->getTrace(), 0, $nbrTrace);
  }

  /**
   *
   * @param array<string, mixed> $trace
   */
  private static function traceToString(array $trace): string {
    $file = isset($trace['file']) && is_string($trace['file']) ? $trace['file'] : '[internal]';

    $line = isset($trace['line']) && is_int($trace['line']) ? (string) $trace['line'] : '0';

    $function = isset($trace['function']) && is_string($trace['function']) ? $trace['function'] : '';

    return sprintf('%s:%s %s()', $file, $line, $function);
  }
}