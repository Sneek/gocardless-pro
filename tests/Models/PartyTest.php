<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Tests\Fixtures;

class PartyTest extends \PHPUnit_Framework_TestCase
{
    use Fixtures;

    /** @test */
    function it_can_set_the_required_attributes()
    {
        $party = BasicParty::fromArray($this->basic_customer_details());

        $this->assertEquals('10 Downing Street', $party->getAddressLine1());
        $this->assertEquals('London', $party->getCity());
        $this->assertEquals('SW1A 2AA', $party->getPostalCode());
        $this->assertEquals('GB', $party->getCountryCode());
    }

    /** @test */
    function it_can_set_optional_attributes()
    {
        $party = BasicParty::fromArray($this->full_customer_details());

        $this->assertSame($party, $party->setId('PA321'));
        $this->assertSame($party, $party->setAddressLine2('Address Line 2'));
        $this->assertSame($party, $party->setAddressLine3('Address Line 3'));
        $this->assertSame($party, $party->setRegion('Somewhere'));

        $this->assertEquals('PA321', $party->getId());
        $this->assertEquals('Address Line 2', $party->getAddressLine2());
        $this->assertEquals('Address Line 3', $party->getAddressLine3());
        $this->assertEquals('Somewhere', $party->getRegion());
        $this->assertEquals('2014-05-08T17:01:06.000Z', $party->getCreatedAt());
    }

    /** @test */
    function it_can_set_the_full_address()
    {
        $party = new BasicParty;

        $return = $party->setAddress('10 Downing Street', 'London', 'SW1A 2AA', 'GB');

        $this->assertSame($party, $return);
        $this->assertEquals('10 Downing Street', $party->getAddressLine1());
        $this->assertEquals('London', $party->getCity());
        $this->assertEquals('SW1A 2AA', $party->getPostalCode());
        $this->assertEquals('GB', $party->getCountryCode());
    }

    /** @test */
    function it_has_nice_aliases_to_modify_common_attributes()
    {
        $party = new BasicParty;

        $party->setStreet('10 Downing Street');

        $this->assertEquals('10 Downing Street', $party->getStreet());
        $this->assertEquals(['address_line1' => '10 Downing Street'], $party->toArray());
    }

    /** @test */
    function it_can_be_converted_to_an_array_for_updating()
    {
        $party = BasicParty::fromArray([
            'id'            => 'CU123',
            'created_at'    => '2014-05-08T17:01:06.000Z',
            'address_line1' => '10 Downing Street',
            'address_line2' => 'Address Line 2',
            'address_line3' => 'Address Line 3',
            'city'          => 'London',
            'postal_code'   => 'SW1A 2AA',
            'region'        => 'Somewhere',
            'country_code'  => 'GB',
        ]);

        $this->assertEquals([
            'address_line1' => '10 Downing Street',
            'city'          => 'London',
            'postal_code'   => 'SW1A 2AA',
            'country_code'  => 'GB',
            'address_line2' => 'Address Line 2',
            'address_line3' => 'Address Line 3',
            'region'        => 'Somewhere',
        ], $party->toArrayForUpdating());
    }
}