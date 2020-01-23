<?php
namespace Lucinda\Logging;

/**
 * Locates and instances loggers based on XML content.
 */
class Wrapper
{
    private $loggers = array();
    
    /**
     * Reads XML tag loggers.{environment}, finds and saves loggers found.
     *
     * @param \SimpleXMLElement $xml XML containing logger settings.
     * @param string $developmentEnvironment Development environment server is running into (eg: local, dev, live)
     * @throws Exception If pointed file doesn't exist or is invalid
     */
    public function __construct(\SimpleXMLElement $xml, string $developmentEnvironment)
    {
        if (empty($xml->loggers)) {
            return;
        }
        $loggersPath = (string) $xml->loggers["path"];
        $this->setLoggers($loggersPath, $xml->loggers->{$developmentEnvironment});
    }
    
    /**
     * Reads XML tag for loggers and saves them for later use.
     *
     * @param string $loggersPath Path to logger classes.
     * @param \SimpleXMLElement $xml XML containing individual logger settings.
     * @throws Exception If pointed file doesn't exist or is invalid
     */
    private function setLoggers(string $loggersPath, \SimpleXMLElement $xml): void
    {
        $list = $xml->xpath("//logger");
        foreach ($list as $xmlProperties) {
            // detects class name
            $className = (string) $xmlProperties["class"];

            // detects wrapper for loggers
            $loggerWrapper = null;
            switch ($className) {
                case "Lucinda\\Logging\\Driver\\File\\Wrapper":
                    $loggerWrapper = new $className($xmlProperties);
                    break;
                case "Lucinda\\Logging\\Driver\\SysLog\\Wrapper":
                    $loggerWrapper = new $className($xmlProperties);
                    break;
                default:
                    if (!$loggersPath || !is_dir($loggersPath)) {
                        throw new Exception("Logger path not found or empty");
                    }
                    $classFinder = new ClassFinder($loggersPath);
                    $className = $classFinder->find($className);
                    $loggerWrapper = new $className($xmlProperties);
                    if (!$loggerWrapper instanceof AbstractLoggerWrapper) {
                        throw new Exception("Logger must be instance of AbstractLoggerWrapper!");
                    }
                    break;
                
            }
            $this->loggers[] = $loggerWrapper->getLogger();
        }
    }
    
    /**
     * Gets detected logger.
     *
     * @return Logger List of loggers found.
     */
    public function getLogger(): Logger
    {
        return new MultiLogger($this->loggers);
    }
}
