<?php

namespace Mamluk\Kipchak\Tests\Unit;

use Mamluk\Kipchak\Components\Http\Codes;
use Mamluk\Kipchak\Tests\TestCase;

class CodesTest extends TestCase
{
    public function testSomething()
    {
        $x = Codes::getCode(100);
        $this->assertEquals('CONTINUE', $x);
    }

}