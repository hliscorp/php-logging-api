<?php
require_once("Logger.php");
require_once("LogFormatter.php");

/**
 * Logs messages/errors into simple files.
 */
class FileLogger extends Logger {
	const EXTENSION = "log";
	
	private $filePath;
	private $rotationPattern;
	private $formatter;
	
	/**
	 * Creates logger instance.
	 * 
	 * @param string $filePath Log file (without extension) and its absolute path.
	 * @param string $rotationPattern PHP date function format by which logs will rotate.
	 * @param LogFormatter $formatter Class responsible in creating and formatting logging message.
	 */
	public function __construct($filePath, $rotationPattern="", LogFormatter $formatter) {
		$this->filePath = $filePath;
		$this->rotationPattern = $rotationPattern;
		$this->formatter = $formatter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::log()
	 */
	protected function log($info, $level) {
		error_log($this->formatter->format($info, $level)."\n", 3, $this->filePath.($this->rotationPattern?"__".date($this->rotationPattern):"").".".self::EXTENSION);
	}
}