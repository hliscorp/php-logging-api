<?php
namespace Test\Lucinda\Logging;
    
use Lucinda\Logging\SysLogger;
use Lucinda\Logging\LogFormatter;
use Lucinda\UnitTest\Validator\Files;

class SysLoggerTest
{
    private $loggerMessages;
    private $loggerErrors;
    private $assertion;
    
    public function __construct()
    {
        $this->loggerMessages = new SysLogger("testlog", new LogFormatter("%v %f %l %m %u %i %a"));
        $this->loggerErrors = new SysLogger("testlog", new LogFormatter("%v %e %f %l %m %u %i %a"));
        $this->assertion = new Files("/var/log/syslog");
        $_SERVER = ["REQUEST_URI"=>"test", "REMOTE_ADDR"=>"127.0.0.1", "HTTP_USER_AGENT"=>"Chrome"];
    }
    
    public function emergency()
    {
        $this->loggerErrors->emergency(new \Exception("info"));
        return $this->assertion->assertContains(LOG_EMERG." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
    
    
    public function alert()
    {
        $this->loggerErrors->alert(new \Exception("info"));
        return $this->assertion->assertContains(LOG_ALERT." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
    
    
    public function critical()
    {
        $this->loggerErrors->critical(new \Exception("info"));
        return $this->assertion->assertContains(LOG_CRIT." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
    
    
    public function error()
    {
        $this->loggerErrors->error(new \Exception("info"));
        return $this->assertion->assertContains(LOG_ERR." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
    
    
    public function warning()
    {
        $this->loggerMessages->warning("message");
        return $this->assertion->assertContains(LOG_WARNING." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }
    
    
    public function notice()
    {
        $this->loggerMessages->notice("message");
        return $this->assertion->assertContains(LOG_NOTICE." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }
    
    
    public function debug()
    {
        $this->loggerMessages->debug("message");
        return $this->assertion->assertContains(LOG_DEBUG." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }
    
    
    public function info()
    {
        $this->loggerMessages->info("message");
        return $this->assertion->assertContains(LOG_INFO." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }
        

}
