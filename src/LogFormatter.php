<?php
namespace Lucinda\Logging;
/**
 * Class responsible in formatting log message for text-based loggers (eg: syslog or file) based on a pattern-match concept.
 */
class LogFormatter {
	private $pattern;
	
	/**
	 * Creates instance and saves message pattern.
	 * 
	 * @param string $pattern Log message pattern (eg: %d %u %f %l %m)
	 */
	public function __construct($pattern) {
		$this->pattern = $pattern;
	}
	
	/**
	 * Builds log message based on global pattern and info to be logged.
	 * 
	 * @param string|\Exception|\Throwable $info Information that needs being logged
	 * @param integer $level Log level (see: https://tools.ietf.org/html/rfc5424) 
	 * @return string Compiled log message ready to be saved.
	 */
	public function format($info, $level) {
		$message = $this->pattern;
		$message = str_replace("%d",date("Y-m-d H:i:s"), $message);
		$message = str_replace("%v", $level, $message);
		if($info instanceof \Exception || $info instanceof \Throwable) {
			$message = str_replace("%e",  get_class($info), $message);
			$message = str_replace("%f",  $info->getFile(), $message);
			$message = str_replace("%l",  $info->getLine(), $message);
			$message = str_replace("%m",  $info->getMessage(), $message);
		} else {
			$trace = debug_backtrace()[2];
			$message = str_replace("%f",  $trace["file"], $message);
			$message = str_replace("%l",  $trace["line"], $message);
			$message = str_replace("%m",  $info, $message);
		}
		if(!empty($_SERVER['REQUEST_URI'])) {
			$message = str_replace("%u", $_SERVER['REQUEST_URI'], $message);
		}		
		return $message;
	}
}