<?php
require_once("Logger.php");
/**
 * Logs messages/errors into syslog service.
 */
class SysLogger extends Logger {
	private $applicationName;
	
	/**
	 * Creates a logger instance.
	 * @param string $applicationName Name of your application to appear in log lines.
	 */
	public function __construct($applicationName) {
		$this->applicationName = $applicationName;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::getErrorInfo()
	 */
	protected function getErrorInfo(Exception $exception) {
		return get_class($exception)." ".$exception->getFile()." ".$exception->getLine()." ".$exception->getMessage();
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::getMessageInfo()
	 */
	protected function getMessageInfo($message) {
		$trace = debug_backtrace()[1];
		return $trace["file"]." ".$trace["line"]." ".$message;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::log()
	 */
	protected function log($message, $logLevel) {
		openlog($this->applicationName, LOG_NDELAY, LOG_USER);
		syslog($logLevel, $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']." ".$message);
		closelog();
	}
}