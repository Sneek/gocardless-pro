<?php
namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\Mandate;
use GoCardless\Pro\Models\RedirectFlow;

class RedirectFlowTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_constructed_with_the_required_bank_accounts()
    {
        $mandate  = Mandate::fromArray(['id' => 'MD111']);
        $creditor = Creditor::fromArray(['id' => 'CR111']);

        $redirectFlow = new RedirectFlow($creditor, $mandate);

        $this->assertAttributeSame($mandate, 'mandate', $redirectFlow);
        $this->assertAttributeSame($creditor, 'creditor', $redirectFlow);
    }

    /** @test */
    function it_uses_bacs_by_default()
    {
        $this->assertEquals('bacs', (new RedirectFlow)->getScheme());
    }

    /** @test */
    function it_returns_the_current_scheme()
    {
        $this->assertTrue((new RedirectFlow)->useBacs()->isBacs());
        $this->assertTrue((new RedirectFlow)->useSepaCore()->isSepaCore());
    }

    /** @test */
    function it_can_be_converted_an_array_for_the_api()
    {
        $redirectFlow = (new RedirectFlow(
            Creditor::fromArray(['id' => 'CR111']),
            Mandate::fromArray(['id' => 'MD111'])
        ))->useSepaCore();

        $this->assertEquals([
            'scheme' => 'sepa_core',
            'links'  => [
                'creditor' => 'CR111',
                'mandate'  => 'MD111',
            ],
        ], $redirectFlow->toArray());
    }

    /** @test */
    function it_can_be_created_from_an_api_response()
    {
        $redirectFlow = RedirectFlow::fromArray([
            'id'                   => 'RE123',
            'created_at'           => '2014-05-08T17:01:06.000Z',
            'scheme'               => 'bacs',
            'session_token'        => 'session_id',
            'success_redirect_url' => 'http://www.mywebsite.com/success',
            'redirect_url'         => 'http://pay.gocardless.dev/flow/RE123',
            'links'                => [
                'creditor' => 'CR123',
                'mandate'  => 'MD123',
            ]
        ]);

        $this->assertEquals('RE123', $redirectFlow->getId());
        $this->assertEquals('2014-05-08T17:01:06.000Z', $redirectFlow->getCreatedAt());
        $this->assertEquals('bacs', $redirectFlow->getScheme());
        $this->assertEquals('session_id', $redirectFlow->getSessionToken());
        $this->assertEquals('http://www.mywebsite.com/success', $redirectFlow->getSuccessRedirectUrl());
        $this->assertEquals('http://pay.gocardless.dev/flow/RE123', $redirectFlow->getRedirectUrl());
        $this->assertEquals('CR123', $redirectFlow->getLink('creditor'));
        $this->assertEquals('MD123', $redirectFlow->getLink('mandate'));
    }

    /** @test */
    function it_can_set_the_success_url()
    {
        $redirectFlow = new RedirectFlow;

        $return = $redirectFlow->setSuccessRedirectUrl('http://foo.com/redirect');

        $this->assertSame($redirectFlow, $return);
        $this->assertEquals('http://foo.com/redirect', $redirectFlow->getSuccessRedirectUrl());
    }
}
