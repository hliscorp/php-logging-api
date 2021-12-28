<?php
namespace Lucinda\Logging\Driver\File;

use Lucinda\Logging\LogFormatter;

/**
 * Logs messages/errors into simple files.
 */
class Logger extends \Lucinda\Logging\Logger
{
    const EXTENSION = "log";
    
    private string $filePath;
    private string $rotationPattern;
    private LogFormatter $formatter;
    
    /**
     * Creates logger instance.
     *
     * @param string $filePath Log file (without extension) and its absolute path.
     * @param string $rotationPattern PHP date function format by which logs will rotate.
     * @param LogFormatter $formatter Class responsible in creating and formatting logging message.
     */
    public function __construct(string $filePath, string $rotationPattern="", LogFormatter $formatter)
    {
        $this->filePath = $filePath;
        $this->rotationPattern = $rotationPattern;
        $this->formatter = $formatter;
    }
    
    /**
     * Performs the act of logging.
     *
     * @param string|\Throwable $info Information that needs being logged
     * @param integer $level Log level (see: https://tools.ietf.org/html/rfc5424)
     */
    protected function log(string|\Throwable $info, int $level): void
    {
        error_log($this->formatter->format($info, $level)."\n", 3, $this->filePath.($this->rotationPattern?"__".date($this->rotationPattern):"").".".self::EXTENSION);
    }
}
