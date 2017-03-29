<?php
require_once("LoggingEnvironment.php");

/**
 * Logs messages/errors into SQL database. Requires a table with this structure (example @ MySQL):
 * 
 	CREATE TABLE {NAME}
 	(
 	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
 	uid CHAR(32) NOT NULL,
 	level TINYINT UNSIGNED NOT NULL,
 	url VARCHAR({SIZE}) NOT NULL,
 	type VARCHAR({SIZE}) NOT NULL,
 	file VARCHAR({SIZE}) NOT NULL,
 	line SMALLINT UNSIGNED NOT NULL,
 	message TEXT NOT NULL,
 	environment TEXT NOT NULL,
 	exception TEXT NOT NULL,
 	date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 	primary key(id),
 	key(uid)
 	) Engine=MyISAM DEFAULT CHARSET=utf8; 	
 * 
 * @requires php-sql-api (https://github.com/aherne/php-sql-api)
 */
class SQLLogger extends Logger {
	const LOGGING_TYPE = "Log";
	private $statement;

	/**
	 * Creates a logger instance.
	 * @param string $tableName Name of table in which logs will be saved into.
	 * @param SQLConnection $connection Connection that's going to be used for saving logs.
	 * @param string $rotationPattern PHP date function format by which tables will rotate.
	 */
	public function __construct($tableName, SQLConnection $connection, $rotationPattern="Y_m_d") {
		$this->createStatement($tableName, $rotationPattern, $connection);
	}
	
	/**
	 * Creates a prepared statement ready to be bound.
	 * 
	 * @param string $tableName
	 * @param string $rotationPattern
	 * @param SQLConnection $connection
	 */
	private function createStatement($tableName, $rotationPattern, SQLConnection $connection) {
		$this->statement = $connection->createPreparedStatement();
		$this->statement->prepare("
		INSERT INTO ".$tableName.($rotationPattern?"__".date($rotationPattern):"")."
			(uid, level, url, type, file, line, message, environment, exception)
        VALUES
			(:uid, :level, :url, :type, :file, :line, :message, :environment, :exception)");
	}
	
	/**
	 * Gets environment of logging.
	 * 
	 * @return LoggingEnvironment
	 */
	private function getEnvironmentInfo() {
		$environment = new LoggingEnvironment();
		$environment->get = (!empty($_GET)?$_GET:array());
		$environment->post = (!empty($_POST)?$_POST:array());
		$environment->server = (!empty($_SERVER)?$_SERVER:array());;
		$environment->files = (!empty($_FILES)?$_FILES:array());;
		$environment->cookies = (!empty($_COOKIE)?$_COOKIE:array());;
		$environment->session = (!empty($_SESSION)?$_SESSION:array());;
		return $environment;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::getErrorInfo()
	 */
	protected function getErrorInfo(Exception $exception){
		// return an array
		return array(
			":url"=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
			":type"=>get_class($exception),
			":file"=>$exception->getFile(),
			":line"=>$exception->getLine(),
			":message"=>$exception->getMessage(),
			":environment"=>serialize($this->getEnvironmentInfo()),
			":exception"=>serialize($exception)
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::getMessageInfo()
	 */
	protected function getMessageInfo($message) {
		// return a query
		$trace = debug_backtrace()[1];
		return array(
			":url"=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
			":type"=>self::LOGGING_TYPE,
			":file"=>$trace["file"],
			":line"=>$trace["line"],
			":message"=>$message,
			":environment"=>serialize($this->getEnvironmentInfo()),
			":exception"=>""
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see Logger::log()
	 */
	protected function log($info, $level) {
		try {
			$info[":level"] = $level;
			$info[":uid"] = md5($info[":type"]."#".$info[":file"]."#".$info[":line"].$info[":message"]);
			$this->statement->execute($info);
		} catch(Exception $e) {}
	}
}