<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Mandate;
use GoCardless\Pro\Models\Subscription;

class SubscriptionTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    function it_returns_the_current_status()
    {
        $this->assertTrue(Subscription::fromArray(['status' => 'active'])->isActive());
        $this->assertTrue(Subscription::fromArray(['status' => 'cancelled'])->isCancelled());
    }

    /** @test */
    function it_has_shortcut_methods()
    {
        $subscription = new Subscription;
        $mandate = Mandate::fromArray(['id' => 'SB123']);

        $subscription->collect(300, 'GBP')->from('2015-08-19')->everyMonth()->until('2016-07-19')->using($mandate);

        $this->assertSame(300, $subscription->getAmount());
        $this->assertSame('GBP', $subscription->getCurrency());
        $this->assertSame('2015-08-19', $subscription->getStartAt());
        $this->assertSame('2016-07-19', $subscription->getEndAt());
        $this->assertSame(1, $subscription->getInterval());
        $this->assertSame('monthly', $subscription->getIntervalUnit());
        $this->assertAttributeSame($mandate, 'mandate', $subscription);
    }

    /** @test */
    function it_can_be_converted_an_array_for_the_api()
    {
        $subscription = new Subscription;
        $subscription->setAmount(300)->setCurrency('GBP')->setStartAt('2015-08-19')->setIntervalUnit('weekly')
                ->setInterval(2)
                ->setName('Test')
                ->setMandate(Mandate::fromArray(['id' => 'MD123']));

        $this->assertEquals([
            'amount'       => '300',
            'currency'     => 'GBP',
            'start_at'     => '2015-08-19',
            'interval_unit'=> 'weekly',
            'interval'     => '2',
            'name'         => 'Test',
            'links'        => [
                'mandate' => 'MD123',
            ]
        ], $subscription->toArray());
    }

    /** @test */
    function it_can_be_created_from_an_api_response()
    {
        $subscription = Subscription::fromArray([
            'id'               => 'SB123',
            'created_at'       => '2014-10-20T17:01:06.000Z',
            'amount'           => '2500',
            'currency'         => 'GBP',
            'status'           => 'active',
            'name'             => 'Monthly Magazine',
            'start_at'         => '2014-11-03',
            'end_at'           => null,
            'interval'         => 1,
            'interval_unit'    => 'monthly',
            'day_of_month'     => 1,
            'month'            => null,
            'payment_reference'=> null,
            'upcoming_payments'=> [
                  ['charge_date' => '2014-11-03', 'amount' => 2500],
                  ['charge_date' => '2014-12-01', 'amount' => 2500],
                  ['charge_date' => '2015-01-02', 'amount' => 2500],
                  ['charge_date' => '2015-02-02', 'amount' => 2500],
                  ['charge_date' => '2015-03-02', 'amount' => 2500],
                  ['charge_date' => '2015-04-01', 'amount' => 2500],
                  ['charge_date' => '2015-05-01', 'amount' => 2500],
                  ['charge_date' => '2015-06-01', 'amount' => 2500],
                  ['charge_date' => '2015-07-01', 'amount' => 2500],
                  ['charge_date' => '2015-08-03', 'amount' => 2500]
            ],
            'metadata'         => [
                'order_no' => 'ABCD1234',
            ],
            'links'            => [
                'mandate'  => 'MD123',
            ]
        ]);

        $this->assertEquals('SB123', $subscription->getId());
        $this->assertEquals('2014-10-20T17:01:06.000Z', $subscription->getCreatedAt());
        $this->assertSame(2500, $subscription->getAmount());
        $this->assertEquals('GBP', $subscription->getCurrency());
        $this->assertEquals('active', $subscription->getStatus());
        $this->assertEquals('Monthly Magazine', $subscription->getName());
        $this->assertEquals('2014-11-03', $subscription->getStartAt());
        $this->assertNull($subscription->getEndAt());
        $this->assertSame(1, $subscription->getInterval());
        $this->assertEquals('mnthly', $subscription->getIntervalUnit());
        $this->assertSame(1, $subscription->getDayOfMonth());
        $this->assertNull($subscription->getMonth());
        $this->assertNull($subscription->getPaymentReference());
    }

    /** @test */
    function it_allows_the_name_to_be_set()
    {
        $subscription = new Subscription;

        $this->assertSame($subscription, $subscription->setName('FooBar'));
        $this->assertEquals('FooBar', $subscription->getName());
    }
    /** @test */
    function it_allows_the_reference_to_be_set()
    {
        $subscription = new Subscription;

        $this->assertSame($subscription, $subscription->setPaymentReference('FooBar'));
        $this->assertEquals('FooBar', $subscription->getPaymentReference());
    }
}