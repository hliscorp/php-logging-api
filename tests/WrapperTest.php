<?php
namespace Test\Lucinda\Logging;
    
use Lucinda\Logging\Wrapper;
use Lucinda\UnitTest\Validator\Files;

class WrapperTest
{
    public function __construct()
    {
        $_SERVER = ["REQUEST_URI"=>"test", "REMOTE_ADDR"=>"127.0.0.1", "HTTP_USER_AGENT"=>"Chrome"];
    }

    public function getLogger()
    {
        $wrapper = new Wrapper(simplexml_load_file("unit-tests.xml"), "local");
        $logger = $wrapper->getLogger();
        $result = [];
        $logger->emergency(new \Exception("error"));
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_EMERG." Exception ".__FILE__." ".(__LINE__-1)." error test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_EMERG." Exception ".__FILE__." ".(__LINE__-2)." error test 127.0.0.1 Chrome");
        return $result;
    }
        

}
