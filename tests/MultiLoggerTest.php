<?php
namespace Test\Lucinda\Logging;
    
use Lucinda\Logging\Wrapper;
use Lucinda\UnitTest\Validator\Files;

class MultiLoggerTest
{
    private $logger;
    
    public function __construct()
    {
        $wrapper = new Wrapper(simplexml_load_file("unit-tests.xml"), "local");
        $this->logger = $wrapper->getLogger();
    }

    public function emergency()
    {
        $result = [];
        $this->logger->emergency(new \Exception("error"));
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_EMERG." Exception ".__FILE__." ".(__LINE__-1)." error test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_EMERG." Exception ".__FILE__." ".(__LINE__-2)." error test 127.0.0.1 Chrome");
        return $result;
    }
        

    public function alert()
    {
        $result = [];
        $this->logger->alert(new \Exception("error"));
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_ALERT." Exception ".__FILE__." ".(__LINE__-1)." error test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_ALERT." Exception ".__FILE__." ".(__LINE__-2)." error test 127.0.0.1 Chrome");
        return $result;
    }
        

    public function critical()
    {
        $result = [];
        $this->logger->critical(new \Exception("error"));
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_CRIT." Exception ".__FILE__." ".(__LINE__-1)." error test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_CRIT." Exception ".__FILE__." ".(__LINE__-2)." error test 127.0.0.1 Chrome");
        return $result;
    }
        

    public function error()
    {
        $result = [];
        $this->logger->error(new \Exception("error"));
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_ERR." Exception ".__FILE__." ".(__LINE__-1)." error test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_ERR." Exception ".__FILE__." ".(__LINE__-2)." error test 127.0.0.1 Chrome");
        return $result;
    }
        

    public function warning()
    {
        $result = [];
        $this->logger->warning("message");
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_WARNING." %e ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_WARNING." %e ".__FILE__." ".(__LINE__-2)." message test 127.0.0.1 Chrome");
        return $result;
    }
        

    public function notice()
    {
        $result = [];
        $this->logger->notice("message");
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_NOTICE." %e ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_NOTICE." %e ".__FILE__." ".(__LINE__-2)." message test 127.0.0.1 Chrome");
        return $result;
    }
        

    public function debug()
    {
        $result = [];
        $this->logger->debug("message");
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_DEBUG." %e ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_DEBUG." %e ".__FILE__." ".(__LINE__-2)." message test 127.0.0.1 Chrome");
        return $result;
    }
        

    public function info()
    {
        $result = [];
        $this->logger->info("message");
        $result[] = (new Files("messages__".date("Y-m-d").".log"))->assertContains(date("Y-m-d H:i:s")." ".LOG_INFO." %e ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
        $result[] = (new Files("/var/log/syslog"))->assertContains(LOG_INFO." %e ".__FILE__." ".(__LINE__-2)." message test 127.0.0.1 Chrome");
        return $result;
    }
        

}
