<?php
namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Tests\Fixtures;

class CreditorTest extends \PHPUnit_Framework_TestCase
{
    use Fixtures;

    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf('GoCardless\Pro\Models\Creditor', $this->get_basic_creditor());
    }

    /** @test */
    function it_can_access_the_required_attributes()
    {
        $creditor = new Creditor;

        $creditor->setName('The Wine Club');

        $this->assertEquals('The Wine Club', $creditor->getName());
    }

    /** @test */
    function it_can_be_converted_to_and_from_an_array()
    {
        $this->assertEquals($this->basic_creditor_details(), $this->get_basic_creditor()->toArray());
        $this->assertEquals($this->full_creditor_details(), $this->get_full_creditor()->toArray());
    }
}