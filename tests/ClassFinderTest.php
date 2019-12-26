<?php
namespace Test\Lucinda\Logging;
    

use Lucinda\Logging\ClassFinder;
use Lucinda\UnitTest\Result;

class ClassFinderTest
{

    public function find()
    {
        $classFinder = new ClassFinder(dirname(__DIR__)."/drivers/File");
        return new Result($classFinder->find("Lucinda\Logging\Driver\File\Wrapper")=="Lucinda\Logging\Driver\File\Wrapper");
        
    }
        

}
