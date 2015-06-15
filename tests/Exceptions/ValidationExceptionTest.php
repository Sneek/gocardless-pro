<?php
namespace GoCardless\Pro\Tests\Exceptions;

use GoCardless\Pro\Exceptions\ValidationException;

class ValidationExceptionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $ex = new ValidationException('A simple message', ['foo' => 'bar']);

        $this->assertEquals('A simple message', $ex->getMessage());
        $this->assertEquals(['foo' => 'bar'], $ex->errors());
        $this->assertEquals(['foo' => 'bar'], $ex->getErrors());
    }
}