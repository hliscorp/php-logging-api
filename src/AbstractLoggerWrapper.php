<?php
namespace Lucinda\Logging;

/**
 * Implements an abstract converter from an XML line (child of loggers.{environment}) to a Logger instance @ LoggingAPI
 */
abstract class AbstractLoggerWrapper
{
    protected Logger $logger;

    /**
     * Calls children to return a \Lucinda\Logger instance from matching "logger" XML tag
     *
     * @param \SimpleXMLElement $xml XML tag that is child of loggers.(environment)
     * @throws ConfigurationException
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->logger = $this->setLogger($xml);
    }
    
    /**
     * Detects Logger instance based on XML tag supplied
     *
     * @param \SimpleXMLElement $xml XML tag that is child of loggers.(environment)
     * @return Logger
     * @throws ConfigurationException If resources referenced in XML do not exist or do not extend/implement required blueprint.
     */
    abstract protected function setLogger(\SimpleXMLElement $xml): Logger;
    
    /**
     * Gets detected logger
     *
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }
}
