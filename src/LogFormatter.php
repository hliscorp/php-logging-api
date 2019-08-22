<?php
namespace Lucinda\Logging;

/**
 * Formats log message for text-based loggers (eg: syslog or file) based on a combination of following placeholders:
 * - %d: timestamp message has occurred
 * - %v: syslog priority level
 * - %e: thrown exception class
 * - %f: thrown exception file
 * - %l: thrown exception line
 * - %m: thrown exception message
 * - %u: uri in which message has occurred
 */
class LogFormatter
{
    private $pattern;
    
    /**
     * Creates instance and saves message pattern.
     *
     * @param string $pattern Log message pattern (eg: %d %u %f %l %m)
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }
    
    /**
     * Builds log message based on global pattern and info to be logged.
     *
     * @param string|\Exception|\Throwable $info Information that needs being logged
     * @param integer $level Log level (see: https://tools.ietf.org/html/rfc5424)
     * @return string Compiled log message ready to be saved.
     */
    public function format($info, $level)
    {
        $message = $this->pattern;
        $message = str_replace("%d", date("Y-m-d H:i:s"), $message);
        $message = str_replace("%v", $level, $message);
        if ($info instanceof \Exception || $info instanceof \Throwable) {
            $message = str_replace("%e", get_class($info), $message);
            $message = str_replace("%f", $info->getFile(), $message);
            $message = str_replace("%l", $info->getLine(), $message);
            $message = str_replace("%m", $info->getMessage(), $message);
        } else {
            $trace = debug_backtrace()[2];
            $message = str_replace("%f", $trace["file"], $message);
            $message = str_replace("%l", $trace["line"], $message);
            $message = str_replace("%m", $info, $message);
        }
        if (!empty($_SERVER['REQUEST_URI'])) {
            $message = str_replace("%u", $_SERVER['REQUEST_URI'], $message);
        }
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $message = str_replace("%i", $_SERVER['REMOTE_ADDR'], $message);
        }
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $message = str_replace("%a", $_SERVER['HTTP_USER_AGENT'], $message);
        }
        return $message;
    }
}
