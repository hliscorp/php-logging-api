<?php
/**
 * Defines logging blueprints.
 */
abstract class Logger {
	/**
	 * Called when system encounters an error likely to make all sessions abort.
	 * 
	 * @param Exception|Throwable $exception
	 */
	public function emergency($exception) {
		$this->log($exception, LOG_EMERG);
	}
	
	/**
	 * Called when system encounters an error that caused current session to abort and requires immediate intervention.
	 * 
	 * @param Exception|Throwable $exception
	 */
	public function alert($exception) {
		$this->log($exception, LOG_ALERT);
	}
	
	/**
	 * Called when system encounters an error that caused current session to abort.
	 * 
	 * @param Exception|Throwable $exception
	 */
	public function critical($exception) {
		$this->log($exception, LOG_CRIT);
	}

	/**
	 * Called when system encounters an error that caused a block of code to malfunction.
	 *
	 * @param Exception|Throwable $exception
	 */
	public function error($exception) {
		$this->log($exception, LOG_ERR);
	}

	/**
	 * Called when system encounters a situation likely to cause errors in the future.
	 *
	 * @param Exception|Throwable $e
	 */
	public function warning($message) {
		$this->log($message, LOG_WARNING);
	}
	
	/**
	 * Called when system encounters a situation of some concern.
	 *
	 * @param Exception|Throwable $e
	 */
	public function notice($message) {
		$this->log($message, LOG_NOTICE);
	}
	
	/**
	 * Called when programmer wants to log a debugging message to provider
	 * 
	 * @param string $message
	 */
	public function debug($message) {
		$this->log($message, LOG_DEBUG);
	}
	
	/**
	 * Called when user wants to log a message to provider
	 * 
	 * @param string $message
	 */
	public function info($message) {
		$this->log($message, LOG_INFO);
	}
	
	
	/**
	 * Performs the act of logging.
	 * 
	 * @param string|Exception|Throwable $info Information that needs being logged
	 * @param integer $level Log level (see: https://tools.ietf.org/html/rfc5424) 
	 */
	abstract protected function log($info, $level);
}