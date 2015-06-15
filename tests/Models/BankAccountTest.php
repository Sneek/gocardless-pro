<?php
namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Tests\Fixtures;

class BankAccountTest extends \PHPUnit_Framework_TestCase
{
    use Fixtures;

    /** @test */
    function it_can_set_the_attributes()
    {
        $account = new BasicBankAccount;

        $this->assertSame($account, $account->setAccountHolderName('Mr John Doe'));
        $this->assertSame($account, $account->setAccountNumber('55997711'));
        $this->assertSame($account, $account->setBankCode('BANK123'));
        $this->assertSame($account, $account->setIban('IBAN123'));
        $this->assertSame($account, $account->setCountryCode('GB'));
        $this->assertSame($account, $account->setCurrency('GBP'));

        $this->assertAttributeEquals('Mr John Doe', 'account_holder_name', $account);
        $this->assertAttributeEquals('55997711', 'account_number', $account);
        $this->assertAttributeEquals('BANK123', 'bank_code', $account);
        $this->assertAttributeEquals('IBAN123', 'iban', $account);
        $this->assertAttributeEquals('GB', 'country_code', $account);
        $this->assertAttributeEquals('GBP', 'currency', $account);
    }

    /** @test */
    function it_can_access_the_properties()
    {
        $account = BasicBankAccount::fromArray($this->full_bank_account_details());

        $this->assertEquals('BA123', $account->getId());
        $this->assertEquals('2014-05-08T17:01:06.000Z', $account->getCreatedAt());
        $this->assertEquals('55779911', $account->getAccountNumber());
        $this->assertEquals('200000', $account->getBranchCode());
        $this->assertEquals('Mr John Doe', $account->getAccountHolderName());
        $this->assertEquals('11', $account->getAccountNumberEnding());
        $this->assertEquals('GB', $account->getCountryCode());
        $this->assertEquals('GBP', $account->getCurrency());
        $this->assertEquals('BARCLAYS BANK PLC', $account->getBankName());
        $this->assertEquals('BANK_CODE', $account->getBankCode());
        $this->assertEquals('IBAN', $account->getIban());
        $this->assertTrue($account->isEnabled());
        $this->assertFalse($account->isDisabled());
    }

    /** @test */
    function it_has_nice_aliases_for_confusing_properties()
    {
        $account = new BasicBankAccount;

        $account->setSortCode('200000');

        $this->assertEquals('200000', $account->getSortCode());
        $this->assertAttributeEquals('200000', 'branch_code', $account);
    }

    /** @test */
    function it_has_a_factory_method_to_create_with_an_id()
    {
        $account = BasicBankAccount::withId('1234');

        $this->assertInstanceOf('GoCardless\Pro\Tests\Models\BasicBankAccount', $account);
        $this->assertEquals('1234', $account->getId());
    }

    /** @test */
    function it_can_be_created_from_an_array()
    {
        $response = [
            'id'                    => 'BA123',
            'created_at'            => '2014-05-08T17:01:06.000Z',
            'account_holder_name'   => 'Frank Osborne',
            'account_number_ending' => '11',
            'country_code'          => 'GB',
            'currency'              => 'GBP',
            'bank_name'             => 'BARCLAYS BANK PLC',
            'metadata'              => [],
            'enabled'               => true,
            'links'                 => [
                'foo' => 'BAR312',
            ]
        ];

        $account = BasicBankAccount::fromArray($response);

        $this->assertAttributeEquals('BA123', 'id', $account);
        $this->assertAttributeEquals('2014-05-08T17:01:06.000Z', 'created_at', $account);
        $this->assertAttributeEquals('Frank Osborne', 'account_holder_name', $account);
        $this->assertAttributeEquals('11', 'account_number_ending', $account);
        $this->assertAttributeEquals('GB', 'country_code', $account);
        $this->assertAttributeEquals('GBP', 'currency', $account);
        $this->assertAttributeEquals('BARCLAYS BANK PLC', 'bank_name', $account);
        $this->assertAttributeEquals(true, 'enabled', $account);
    }
}