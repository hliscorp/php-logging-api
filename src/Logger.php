<?php

namespace Lucinda\Logging;

/**
 * Defines logging blueprints.
 */
abstract class Logger
{
    /**
     * Called when system encounters an error likely to make all sessions abort.
     *
     * @param \Throwable $exception
     */
    public function emergency(\Throwable $exception): void
    {
        $this->log($exception, LOG_EMERG);
    }

    /**
     * Called when system encounters an error that caused current session to abort and requires immediate intervention.
     *
     * @param \Throwable $exception
     */
    public function alert(\Throwable $exception): void
    {
        $this->log($exception, LOG_ALERT);
    }

    /**
     * Called when system encounters an error that caused current session to abort.
     *
     * @param \Throwable $exception
     */
    public function critical(\Throwable $exception): void
    {
        $this->log($exception, LOG_CRIT);
    }

    /**
     * Called when system encounters an error that caused a block of code to malfunction.
     *
     * @param \Throwable $exception
     */
    public function error(\Throwable $exception): void
    {
        $this->log($exception, LOG_ERR);
    }

    /**
     * Called when system encounters a situation likely to cause errors in the future.
     *
     * @param string $message
     */
    public function warning(string $message): void
    {
        $this->log($message, LOG_WARNING);
    }

    /**
     * Called when system encounters a situation of some concern.
     *
     * @param string $message
     */
    public function notice(string $message): void
    {
        $this->log($message, LOG_NOTICE);
    }

    /**
     * Called when programmer wants to log a debugging message to provider
     *
     * @param string $message
     */
    public function debug(string $message): void
    {
        $this->log($message, LOG_DEBUG);
    }

    /**
     * Called when user wants to log a message to provider
     *
     * @param string $message
     */
    public function info(string $message): void
    {
        $this->log($message, LOG_INFO);
    }

    /**
     * Performs the act of logging.
     *
     * @param string|\Throwable $info  Information that needs being logged
     * @param integer           $level Log level (see: https://tools.ietf.org/html/rfc5424)
     */
    abstract protected function log(string|\Throwable $info, int $level): void;
}
