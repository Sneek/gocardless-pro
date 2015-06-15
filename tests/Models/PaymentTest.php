<?php
namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Mandate;
use GoCardless\Pro\Models\Payment;

class PaymentTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    function it_returns_the_current_status()
    {
        $this->assertTrue(Payment::fromArray(['status' => 'pending_submission'])->isPendingSubmission());
        $this->assertTrue(Payment::fromArray(['status' => 'submitted'])->isSubmitted());
        $this->assertTrue(Payment::fromArray(['status' => 'confirmed'])->isConfirmed());
        $this->assertTrue(Payment::fromArray(['status' => 'failed'])->isFailed());
        $this->assertTrue(Payment::fromArray(['status' => 'charged_back'])->isChargedBack());
        $this->assertTrue(Payment::fromArray(['status' => 'paid_out'])->isPaidOut());
        $this->assertTrue(Payment::fromArray(['status' => 'cancelled'])->isCancelled());
    }

    /** @test */
    function it_has_shortcut_methods()
    {
        $payment = new Payment;
        $mandate = Mandate::fromArray(['id' => 'MD123']);

        $payment->collect(300, 'GBP')->on('2014-05-19')->using($mandate);

        $this->assertSame(300, $payment->getAmount());
        $this->assertSame('GBP', $payment->getCurrency());
        $this->assertSame('2014-05-19', $payment->getChargeDate());
        $this->assertAttributeSame($mandate, 'mandate', $payment);
    }

    /** @test */
    function it_can_be_converted_an_array_for_the_api()
    {
        $payment = new Payment;
        $payment->setAmount(300)->setCurrency('GBP')->setChargeDate('2014-05-19')
            ->setDescription('My simple description')
            ->setMandate(Mandate::fromArray(['id' => 'MD123']));

        $this->assertEquals([
            'amount'      => '300',
            'currency'    => 'GBP',
            'charge_date' => '2014-05-19',
            'description' => 'My simple description',
            'links'       => [
                'mandate' => 'MD123',
            ]
        ], $payment->toArray());
    }

    /** @test */
    function it_can_be_created_from_an_api_response()
    {
        $payment = Payment::fromArray([
            'id'              => 'PM123',
            'created_at'      => '2014-05-08T17:01:06.000Z',
            'charge_date'     => '2014-05-15',
            'amount'          => '100',
            'currency'        => 'GBP',
            'description'     => null,
            'status'          => 'pending_submission',
            'reference'       => 'WINEBOX001',
            'metadata'        => [
                'order_dispatch_date' => '2014-05-22',
            ],
            'amount_refunded' => '0',
            'links'           => [
                'mandate'  => 'MD123',
                'creditor' => 'CR123',
            ]
        ]);

        $this->assertEquals('PM123', $payment->getId());
        $this->assertEquals('2014-05-08T17:01:06.000Z', $payment->getCreatedAt());
        $this->assertEquals('2014-05-15', $payment->getChargeDate());
        $this->assertSame(100, $payment->getAmount());
        $this->assertSame('GBP', $payment->getCurrency());
        $this->assertNull($payment->getDescription());
        $this->assertEquals('pending_submission', $payment->getStatus());
        $this->assertEquals('WINEBOX001', $payment->getReference());
        $this->assertSame(0, $payment->getAmountRefunded());
    }

    /** @test */
    function it_allows_the_reference_to_be_set()
    {
        $payment = new Payment;

        $this->assertSame($payment, $payment->setReference('FooBar'));
        $this->assertEquals('FooBar', $payment->getReference());
    }
}