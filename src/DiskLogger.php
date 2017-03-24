<?php
require_once("Logger.php");
/**
 * Abstracts logging into log files
 */
abstract class DiskLogger extends Logger {
	/**
	 * {@inheritDoc}
	 * @see Logger::getErrorInfo()
	 */
	protected function getErrorInfo(Exception $exception) {
		return get_class($exception)."\t".$exception->getFile()."\t".$exception->getLine()."\t".$exception->getMessage();
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::getMessageInfo()
	 */
	protected function getMessageInfo($message) {
		$trace = debug_backtrace()[1];
		return $trace["file"]."\t".$trace["line"]."\t".$message;
	}
}