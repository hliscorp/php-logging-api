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
		return $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\t".get_class($exception)."\t".$exception->getFile()."\t".$exception->getLine()."\t".$exception->getMessage();
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::getMessageInfo()
	 */
	protected function getMessageInfo($message) {
		$trace = debug_backtrace()[1];
		return $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\t".$trace["file"]."\t".$trace["line"]."\t".$message;
	}
}