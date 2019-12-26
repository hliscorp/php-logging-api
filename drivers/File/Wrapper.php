<?php
namespace Lucinda\Logging\Driver\File;

use Lucinda\Logging\Exception;
use Lucinda\Logging\LogFormatter;

/**
 * Logs message into file on disk, whose location varies according to development environment.
 */
class Wrapper extends \Lucinda\Logging\AbstractLoggerWrapper
{
    /**
     * Detects Logger instance based on XML tag supplied
     *
     * @param \SimpleXMLElement $xml XML tag that is child of loggers.(environment)
     * @return Logger
     * @throws Exception If resources referenced in XML do not exist or do not extend/implement required blueprint.
     */
    protected function setLogger(\SimpleXMLElement $xml): \Lucinda\Logging\Logger
    {
        $filePath = (string) $xml["path"];
        if (!$filePath) {
            throw new Exception("Attribute 'path' is mandatory");
        }
        
        $pattern= (string) $xml["format"];
        if (!$pattern) {
            throw new Exception("Attribute 'format' is mandatory");
        }
        
        return new Logger($filePath, (string) $xml["rotation"], new LogFormatter($pattern));
    }
}
