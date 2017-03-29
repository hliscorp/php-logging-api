<?php
/**
 * Struct collecting all information about environment in which logging has occurred.
 */
class LoggingEnvironment {
	public $files;
	public $get;
	public $post;
	public $server;
	public $cookie;
	public $session;
}