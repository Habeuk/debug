<?php

namespace Stephane888\Debug\Logger;


Interface DebugLoggerInterface
{


    /**
     * Add debug information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function debug($message, array $contenData = [], $fileName = '');

    /**
     * Add info information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function info($message, array $contenData = [], $fileName = "info");

    /**
     * Add notice information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function notice($message, array $contenData = [], $fileName = "notice");

    /**
     * Add warning information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function warning($message, array $contenData = [], $fileName = "warning");

    /**
     * Add error information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function error($message, array $contenData = [], $fileName = "error");

    /**
     * Add critical information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function critical($message, array $contenData = [], $fileName = "critical");

    /**
     * Add alert information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function alert($message, array $contenData = [], $fileName = "alert");

    /**
     * Add emergency information to a log file
     * @param string $message The message to write in the log file
     * @param array $contenData The context you want to add in the log (eg: an array of data)
     * @param string $fileName The name of the log file
     */
    public function emergency($message, array $contenData = [], $fileName = "emergency");
}
