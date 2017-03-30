<?php
require_once("Logger.php");

/**
 * Logs messages/errors into simple files.
 */
class FileLogger extends Logger {
	const EXTENSION = "log";
	private $filePath;
	private $rotationPattern;
	
	/**
	 * Creates logger instance.
	 * 
	 * @param string $filePath Log file (without extension) and its absolute path.
	 * @param string $rotationPattern PHP date function format by which logs will rotate.
	 */
	public function __construct($filePath, $rotationPattern="Y_m_d") {
		$this->filePath = $filePath;
		$this->rotationPattern = $rotationPattern;
	}
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
	
	/**
	 * {@inheritDoc}
	 * @see DiskLogger::save()
	 */
	protected function log($message, $level) {
		error_log(date("Y-m-d H:i:s")."\t".$level."\t".$_SERVER['REQUEST_URI']."\t".$message."\n", 3, $this->filePath.($this->rotationPattern?"__".date($this->rotationPattern):"").".".self::EXTENSION);
	}
}