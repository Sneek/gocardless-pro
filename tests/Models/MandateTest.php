<?php
namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\CustomerBankAccount;
use GoCardless\Pro\Models\Mandate;

class MandateTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_constructed_with_the_required_bank_accounts()
    {
        $customerBankAccount = CustomerBankAccount::fromArray(['id' => 'BA111']);
        $creditor            = Creditor::fromArray(['id' => 'CR111']);

        $mandate = new Mandate($customerBankAccount, $creditor);

        $this->assertAttributeSame($customerBankAccount, 'customer_bank_account', $mandate);
        $this->assertAttributeSame($creditor, 'creditor', $mandate);
    }

    /** @test */
    function it_uses_bacs_by_default()
    {
        $this->assertEquals('bacs', (new Mandate)->getScheme());
    }

    /** @test */
    function it_returns_the_current_scheme()
    {
        $this->assertTrue((new Mandate)->useBacs()->isBacs());
        $this->assertTrue((new Mandate)->useSepaCore()->isSepaCore());
    }

    /** @test */
    function it_returns_the_current_status()
    {
        $this->assertTrue(Mandate::fromArray(['status' => 'pending_submission'])->isPendingSubmission());
        $this->assertTrue(Mandate::fromArray(['status' => 'submitted'])->isSubmitted());
        $this->assertTrue(Mandate::fromArray(['status' => 'active'])->isActive());
        $this->assertTrue(Mandate::fromArray(['status' => 'failed'])->isFailed());
        $this->assertTrue(Mandate::fromArray(['status' => 'cancelled'])->isCancelled());
        $this->assertTrue(Mandate::fromArray(['status' => 'expired'])->isExpired());
    }

    /** @test */
    function it_can_be_converted_an_array_for_the_api()
    {
        $mandate = (new Mandate(
            CustomerBankAccount::fromArray(['id' => 'BA111']),
            Creditor::fromArray(['id' => 'CR111'])
        ))->useSepaCore();

        $this->assertEquals([
            'scheme' => 'sepa_core',
            'links'  => [
                'customer_bank_account' => 'BA111',
                'creditor'              => 'CR111',
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