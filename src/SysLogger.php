<?php
require_once("Logger.php");
require_once("LogFormatter.php");

/**
 * Logs messages/errors into syslog service.
 */
class SysLogger extends Logger {
	private $applicationName;
	private $formatter;
	
	/**
	 * Creates a logger instance.
	 * @param string $applicationName Name of your application to appear in log lines.
	 * @param LogFormatter $formatter Class responsible in creating and formatting logging message.
	 */
	public function __construct($applicationName, LogFormatter $formatter) {
		$this->applicationName = $applicationName;
		$this->formatter = $formatter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::log()
	 */
	protected function log($info, $level) {
		openlog($this->applicationName, LOG_NDELAY, LOG_USER);
		syslog($level, $this->formatter->format($info, $level));
		closelog();
	}
}