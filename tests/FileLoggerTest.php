<?php
namespace Test\Lucinda\Logging;
    
use Lucinda\Logging\FileLogger;
use Lucinda\Logging\LogFormatter;
use Lucinda\UnitTest\Validator\Files;

class FileLoggerTest
{    
    private $loggerMessages;
    private $loggerErrors;
    private $assertion;
    
    public function __construct()
    {   
        $this->loggerMessages = new FileLogger("info", "Y-m-d", new LogFormatter("%d %v %f %l %m %u %i %a"));
        $this->loggerErrors = new FileLogger("info", "Y-m-d", new LogFormatter("%d %v %e %f %l %m %u %i %a"));
        $this->assertion = new Files("info__".date("Y-m-d").".log");
        $_SERVER = ["REQUEST_URI"=>"test", "REMOTE_ADDR"=>"127.0.0.1", "HTTP_USER_AGENT"=>"Chrome"];
    }

    public function emergency()
    {
        $this->loggerErrors->emergency(new \Exception("info"));
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_EMERG." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
        

    public function alert()
    {
        $this->loggerErrors->alert(new \Exception("info"));
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_ALERT." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
        

    public function critical()
    {
        $this->loggerErrors->critical(new \Exception("info"));
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_CRIT." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
        

    public function error()
    {
        $this->loggerErrors->error(new \Exception("info"));
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_ERR." Exception ".__FILE__." ".(__LINE__-1)." info test 127.0.0.1 Chrome");
    }
        

    public function warning()
    {
        $this->loggerMessages->warning("message");
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_WARNING." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }
        

    public function notice()
    {
        $this->loggerMessages->notice("message");        
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_NOTICE." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }
        

    public function debug()
    {
        $this->loggerMessages->debug("message");        
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_DEBUG." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }
        

    public function info()
    {
        $this->loggerMessages->info("message");        
        return $this->assertion->assertContains(date("Y-m-d H:i:s")." ".LOG_INFO." ".__FILE__." ".(__LINE__-1)." message test 127.0.0.1 Chrome");
    }

}
