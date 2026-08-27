<?php
namespace Stephane888\Debug\Logger;

Interface DebugLoggerInterface {

  /**
   * Add debug information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function debug(string|\Stringable $message, array $contenData = [], $fileName = ''): void;

  /**
   * Add info information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function info(string|\Stringable $message, array $contenData = [], $fileName = "info"): void;

  /**
   * Add notice information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function notice(string|\Stringable $message, array $contenData = [], $fileName = "notice"): void;

  /**
   * Add warning information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function warning(string|\Stringable $message, array $contenData = [], $fileName = "warning"): void;

  /**
   * Add error information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function error(string|\Stringable $message, array $contenData = [], $fileName = "error"): void;

  /**
   * Add critical information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function critical(string|\Stringable $message, array $contenData = [], $fileName = "critical"): void;

  /**
   * Add alert information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function alert(string|\Stringable $message, array $contenData = [], $fileName = "alert"): void;

  /**
   * Add emergency information to a log file
   *
   * @param string|\Stringable $message The message to write in the log file
   * @param array<mixed> $contenData The context you want to add in the log (eg: an array of data)
   * @param string $fileName The name of the log file
   */
  public function emergency(string|\Stringable $message, array $contenData = [], $fileName = "emergency"): void;
}
