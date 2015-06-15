<?php
namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;
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
    function it_has_a_shortcut_to_fill_uk_bank_details()
    {
        $account  = new CustomerBankAccount;
        $customer = new Customer;

        $account->withAccountDetails('John Doe', '12345678', '112233', 'GB', $customer);

        $this->assertEquals('John Doe', $account->getAccountHolderName());
        $this->assertEquals('12345678', $account->getAccountNumber());
        $this->assertEquals('112233', $account->getSortCode());
        $this->assertEquals('GB', $account->getCountryCode());
        $this->assertAttributeSame($customer, 'customer', $account);
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

