<?php
use Mamluk\Kipchak\Tests\Unit;
use Mamluk\Kipchak\Components\Http\Codes;

class CodesTest extends \PHPUnit\Framework\TestCase
{
    public function testSomething()
    {
        $x = Codes::getCode(100);
        $this->assertEquals('CONTINUE', $x);
    }

}