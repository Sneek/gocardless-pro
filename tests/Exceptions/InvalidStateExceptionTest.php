<?php namespace GoCardless\Pro\Tests\Exceptions;

use GoCardless\Pro\Exceptions\InvalidStateException;

class InvalidStateExceptionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $ex = new InvalidStateException('Some invalid state', 422, ['foo' => 'bar']);

        $this->assertEquals('Some invalid state', $ex->getMessage());
        $this->assertEquals(422, $ex->getCode());
        $this->assertEquals(['foo' => 'bar'], $ex->errors());
        $this->assertEquals(['foo' => 'bar'], $ex->getErrors());
    }
}