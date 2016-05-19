<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Payment;
use GoCardless\Pro\Models\Refund;

class RefundTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_converted_an_array_for_the_api()
    {
        $refund = new Refund;
        $refund->setAmount(150)
               ->setTotalAmountConfirmation(300)
               ->setReference('Nude Wines refund')
               ->setPayment(Payment::fromArray(['id' => 'PM123']));

        $this->assertEquals([
            'amount'                    => 150,
            'total_amount_confirmation' => 300,
            'reference'                 => 'Nude Wines refund',
            'links'                     => [
                'payment' => 'PM123',
            ]
        ], $refund->toArray());
    }

    /** @test */
    function it_can_be_created_from_an_api_response()
    {
        $refund = Refund::fromArray([
            'id'         => 'RF123',
            'created_at' => '2014-05-08T17:01:06.000Z',
            'amount'     => '100',
            'currency'   => 'GBP',
            'reference'  => 'Nude Wines refund',
            'metadata'   => [
                'reason' => 'late delivery',
            ],
            'links'      => [
                'payment' => 'PM123',
            ],
        ]);

        $this->assertSame('RF123', $refund->getId());
        $this->assertSame('2014-05-08T17:01:06.000Z', $refund->getCreatedAt());
        $this->assertSame(100, $refund->getAmount());
        $this->assertSame('GBP', $refund->getCurrency());
        $this->assertSame('Nude Wines refund', $refund->getReference());
    }

    /** @test */
    function it_has_shortcut_methods()
    {
        $refund  = new Refund;
        $payment = Payment::fromArray(['id' => 'PM123']);

        $refund->of($payment)->returning(100)->totalling(150);

        $this->assertSame(100, $refund->getAmount());
        $this->assertSame(150, $refund->getTotalAmountConfirmation());
        $this->assertAttributeSame($payment, 'payment', $refund);
    }
}