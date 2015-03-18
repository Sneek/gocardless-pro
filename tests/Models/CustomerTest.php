<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Tests\Fixtures;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    use Fixtures;

    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf('GoCardless\Pro\Models\Customer', $this->get_basic_customer());
    }

    /** @test */
    function it_can_access_the_required_attributes()
    {
        $customer = new Customer;

        $customer->setFullName('John', 'Doe')->setEmail('john.doe@gmail.com');

        $this->assertEquals('John', $customer->getGivenName());
        $this->assertEquals('Doe', $customer->getFamilyName());
        $this->assertEquals('john.doe@gmail.com', $customer->getEmail());
    }

    /** @test */
    function it_can_set_the_fullname()
    {
        $customer = new Customer;

        $return = $customer->setFullName('John', 'Doe');

        $this->assertEquals('John', $customer->getGivenName());
        $this->assertEquals('Doe', $customer->getFamilyName());
        $this->assertSame($customer, $return);
    }

    /** @test */
    function it_has_alias_for_setting_the_customer_name()
    {
        $customer = new Customer;

        $customer->setForename('John')->setSurname('Doe');

        $this->assertEquals('John', $customer->getForename());
        $this->assertEquals('Doe', $customer->getSurname());
        $this->assertEquals([
            'given_name' => 'John',
            'family_name' => 'Doe',
        ], $customer->toArray());

        $customer->setFirstName('Jane')->setLastName('Smith');

        $this->assertEquals('Jane', $customer->getFirstName());
        $this->assertEquals('Smith', $customer->getLastName());
        $this->assertEquals([
            'given_name' => 'Jane',
            'family_name' => 'Smith',
        ], $customer->toArray());
    }

    /** @test */
    function it_can_be_converted_to_and_from_an_array()
    {
        $this->assertEquals($this->basic_customer_details(), $this->get_basic_customer()->toArray());
        $this->assertEquals($this->full_customer_details(), $this->get_full_customer()->toArray());
    }
}
