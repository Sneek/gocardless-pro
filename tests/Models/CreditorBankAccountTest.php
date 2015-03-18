<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Tests\Fixtures;

class CreditorBankAccountTest extends \PHPUnit_Framework_TestCase
{
    use Fixtures;

    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(
            'GoCardless\Pro\Models\CreditorBankAccount',
            $this->get_creditor_bank_account()
        );
    }

    /** @test */
    function it_can_be_converted_to_an_array()
    {
        $account = $this->get_creditor_bank_account();

        $this->assertEquals([
            'account_holder_name' => 'Nude Wines',
            'account_number'      => '55779911',
            'branch_code'         => '200000',
            'country_code'        => 'GB',
            'links'               => [
                'creditor' => 'CR123',
            ]
        ], $account->toArray());
    }
}

