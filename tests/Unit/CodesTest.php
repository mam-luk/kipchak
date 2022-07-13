<?php
use Meezaan\Microservice\Tests\Unit;
use Meezaan\Microservice\Components\Http\Codes;

class CodesTest extends \PHPUnit\Framework\TestCase
{
    public function testSomething()
    {
        $x = Codes::getCode(100);
        $this->assertEquals('CONTINUE', $x);
    }

}