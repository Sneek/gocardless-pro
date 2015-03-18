<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\CreditorBankAccount;
use GoCardless\Pro\Models\CustomerBankAccount;
use GoCardless\Pro\Models\Mandate;

class MandateTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_constructed_with_the_required_bank_accounts()
    {
        $customerBankAccount = CustomerBankAccount::fromArray(['id' => 'BA111']);
        $creditorBankAccount = CreditorBankAccount::fromArray(['id' => 'BA222']);

        $mandate = new Mandate($customerBankAccount, $creditorBankAccount);

        $this->assertAttributeSame($customerBankAccount, 'customer_bank_account', $mandate);
        $this->assertAttributeSame($creditorBankAccount, 'creditor_bank_account', $mandate);
    }

    /** @test */
    function it_uses_bacs_by_default()
    {
        $this->assertEquals('bacs', (new Mandate)->getScheme());
    }

    /** @test */
    function it_can_be_converted_an_array_for_the_api()
    {
        $mandate = new Mandate;
        $mandate->setCustomerBankAccount(CustomerBankAccount::fromArray(['id' => 'BA111']))
                ->setCreditorBankAccount(CreditorBankAccount::fromArray(['id' => 'BA222']))
                ->useSepaCore();

        $this->assertEquals([
            'scheme' => 'sepa_core',
            'links'  => [
                'customer_bank_account' => 'BA111',
                'creditor_bank_account' => 'BA222',
            ],
        ], $mandate->toArray());
    }

    /** @test */
    function it_can_be_created_from_an_api_response()
    {
        $mandate = Mandate::fromArray([
            'id'                        => 'MD123',
            'created_at'                => '2014-05-08T17:01:06.000Z',
            'reference'                 => 'REF-123',
            'status'                    => 'pending_submission',
            'scheme'                    => 'bacs',
            'next_possible_charge_date' => '2014-11-10',
            'metadata'                  => [
                'contract' => 'ABCD1234'
            ],
            'links'                     => [
                'customer_bank_account' => 'BA123',
                'creditor'              => 'CR123'
            ]
        ]);

        $this->assertEquals('MD123', $mandate->getId());
        $this->assertEquals('2014-05-08T17:01:06.000Z', $mandate->getCreatedAt());
        $this->assertEquals('REF-123', $mandate->getReference());
        $this->assertEquals('pending_submission', $mandate->getStatus());
        $this->assertEquals('bacs', $mandate->getScheme());
        $this->assertEquals('2014-11-10', $mandate->getNextPossibleChargeDate());
    }
}