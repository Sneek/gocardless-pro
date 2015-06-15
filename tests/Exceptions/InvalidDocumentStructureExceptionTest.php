<?php
namespace GoCardless\Pro\Tests\Exceptions;

use GoCardless\Pro\Exceptions\InvalidDocumentStructureException;

class InvalidDocumentStructureExceptionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $ex = new InvalidDocumentStructureException('A simple message', 400);

        $this->assertEquals('A simple message', $ex->getMessage());
        $this->assertEquals(400, $ex->getCode());
    }
}
