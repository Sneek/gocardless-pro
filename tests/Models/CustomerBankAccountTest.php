<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Tests\Fixtures;

class CustomerBankAccountTest extends \PHPUnit_Framework_TestCase
{
    use Fixtures;

    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(
            'GoCardless\Pro\Models\CustomerBankAccount',
            $this->get_customer_bank_account()
        );
    }

    /** @test */
    function it_can_be_converted_to_an_array()
    {
        $account = $this->get_customer_bank_account();

        $this->assertEquals([
            'account_holder_name' => 'Mr John Doe',
            'account_number'      => '55779911',
            'branch_code'         => '200000',
            'country_code'        => 'GB',
            'links'               => [
                'customer' => $this->get_full_customer()->getId(),
            ]
        ], $account->toArray());
    }
}

