<?php
/**
 * Defines logging blueprints.
 */
abstract class Logger {
	/**
	 * Called when system encounters an error likely to make all sessions abort.
	 * 
	 * @param Exception $e
	 */
	public function emergency(Exception $e) {
		$this->log($this->getErrorInfo($e), LOG_EMERG);
	}
	
	/**
	 * Called when system encounters an error that caused current session to abort and requires immediate intervention.
	 * 
	 * @param Exception $e
	 */
	public function alert(Exception $e) {
		$this->log($this->getErrorInfo($e), LOG_ALERT);
	}
	
	/**
	 * Called when system encounters an error that caused current session to abort.
	 * 
	 * @param Exception $e
	 */
	public function critical(Exception $e) {
		$this->log($this->getErrorInfo($e), LOG_CRIT);
	}

	/**
	 * Called when system encounters an error that caused a block of code to malfunction.
	 *
	 * @param Exception $e
	 */
	public function error(Exception $e) {
		$message = $this->getErrorInfo($e);
		$this->log($message, LOG_ERR);
	}

	/**
	 * Called when system encounters a situation likely to cause errors in the future.
	 *
	 * @param Exception $e
	 */
	public function warning($message) {
		$this->log($this->getMessageInfo($message), LOG_WARNING);
	}
	
	/**
	 * Called when system encounters a situation of some concern.
	 *
	 * @param Exception $e
	 */
	public function notice($message) {
		$this->log($this->getMessageInfo($message), LOG_NOTICE);
	}
	
	/**
	 * Called when programmer wants to log a debugging message to provider
	 * 
	 * @param string $message
	 */
	public function debug($message) {
		$this->log($this->getMessageInfo($message), LOG_DEBUG);
	}
	
	/**
	 * Called when user wants to log a message to provider
	 * 
	 * @param string $message
	 */
	public function info($message) {
		$this->log($this->getMessageInfo($message), LOG_INFO);
	}
	
	/**
	 * Aggregates information to log based on an exception.
	 *  
	 * @param Exception $exception
	 * @return mixed
	 */
	abstract protected function getErrorInfo(Exception $exception);
	
	/**
	 * Aggregates information to log based on a string message.
	 * 
	 * @param string $message
	 * @return mixed
	 */
	abstract protected function getMessageInfo($message);
	
	
	/**
	 * Performs the act of logging.
	 * 
	 * @param mixed $info Information that needs being logged
	 * @param integer $level Log level (see: https://tools.ietf.org/html/rfc5424) 
	 */
	abstract protected function log($info, $level);
}