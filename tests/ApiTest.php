<?php namespace GoCardless\Pro\Tests;

use GoCardless\Pro\Api;
use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\CreditorBankAccount;
use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;
use GoCardless\Pro\Models\Mandate;
use GoCardless\Pro\Models\Payment;
use GuzzleHttp\Client;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    use Fixtures;

    /**
     * @var \GoCardless\Pro\Api
     */
    protected $api;

    public function setUp()
    {
        $config = require __DIR__ . '/../config.php';

        $this->api = new Api(new Client, $config['accessToken'], $config['version']);
    }

    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf('GoCardless\Pro\Api', $this->api);
        $this->assertAttributeInstanceOf('GuzzleHttp\Client', 'client', $this->api);
        $this->assertAttributeNotEmpty('accessToken', $this->api);
        $this->assertAttributeEquals('staging', 'environment', $this->api);
    }

    /** @test */
    function it_can_create_a_creditor()
    {
        $creditor = $this->get_basic_creditor();

        $creditor = $this->api->createCreditor($creditor);

        $this->assertNotNull($creditor->getId());
        $this->assertNotNull($creditor->getCreatedAt());

        return $creditor;
    }

    /** @depends it_can_create_a_creditor */
    function test_it_can_update_a_creditor(Creditor $creditor)
    {
        $creditor->setName('Not So Nude Wines');

        $notSoNudeWines = $this->api->updateCreditor($creditor);

        $this->assertEquals('Not So Nude Wines', $notSoNudeWines->getName());
    }

    /** @test */
    function it_can_list_creditors()
    {
        $creditors = $this->api->listCreditors();

        $this->assertInternalType('array', $creditors);
        foreach ($creditors as $creditor) {
            $this->assertInstanceOf('GoCardless\Pro\Models\Creditor', $creditor);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_creditors()
    {
        $this->guardAgainstSmallNumberOfCreditors();

        $this->assertCount(3, $this->api->listCreditors(['limit' => 3]));
    }

    /** @depends it_can_create_a_creditor */
    function test_it_can_get_a_single_creditor(Creditor $old)
    {
        $new = $this->api->getCreditor($old->getId());

        $this->assertInstanceOf('GoCardless\Pro\Models\Creditor', $new);
        $this->assertEquals($old->toArray(), $new->toArray());

        return $new;
    }

    /** @depends it_can_create_a_creditor */
    function test_it_can_create_creditor_bank_account(Creditor $creditor)
    {
        $account = $this->get_creditor_bank_account($creditor);

        $account = $this->api->createCreditorBankAccount($account);

        $this->assertNotNull($account->getId());
        $this->assertNotNull($account->getCreatedAt());
        $this->assertNotNull($account->getAccountNumberEnding());
        $this->assertNotNull($account->getBankName());
        $this->assertNotNull($account->getCountryCode());
        $this->assertNotNull($account->getCurrency());
        $this->assertTrue($account->isEnabled());
        $this->assertFalse($account->isDisabled());

        return $account;
    }

    /** @test */
    function it_can_list_creditor_bank_accounts()
    {
        $accounts = $this->api->listCreditorBankAccounts();

        $this->assertInternalType('array', $accounts);
        foreach ($accounts as $account) {
            $this->assertInstanceOf('GoCardless\Pro\Models\CreditorBankAccount', $account);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_creditor_bank_accounts()
    {
        $this->guardAgainstSmallNumberOfCreditorBankAccounts();

        $this->assertCount(3, $this->api->listCreditorBankAccounts(['limit' => 3]));
    }

    /** @depends test_it_can_create_creditor_bank_account */
    function test_it_can_get_a_single_creditor_bank_account(CreditorBankAccount $old)
    {
        $new = $this->api->getCreditorBankAccount($old->getId());

        $this->assertInstanceOf('GoCardless\Pro\Models\CreditorBankAccount', $new);
        $this->assertEquals($old->toArray(), $new->toArray());
    }

    /** @depends test_it_can_create_creditor_bank_account */
    function test_it_can_disable_a_creditor_bank_account(CreditorBankAccount $account)
    {
        $account = $this->api->disableCreditorBankAccount($account->getId());

        $this->assertInstanceOf('GoCardless\Pro\Models\CreditorBankAccount', $account);
        $this->assertTrue($account->isDisabled());
    }

    /** @test */
    function it_can_create_a_customer()
    {
        $customer = $this->get_basic_customer();

        $customer = $this->api->createCustomer($customer);

        $this->assertInstanceOf('GoCardless\Pro\Models\Customer', $customer);
        $this->assertNotNull($customer->getId());
        $this->assertNotNull($customer->getCreatedAt());

        return $customer;
    }

    /** @depends it_can_create_a_customer */
    function test_it_can_update_a_customer(Customer $customer)
    {
        $customer->setFullName('Jane', 'Smith');
        $customer->setEmail('jane.smith@example.com');

        $jane = $this->api->updateCustomer($customer);

        $this->assertEquals('Jane', $jane->getForename());
        $this->assertEquals('Smith', $jane->getSurname());
        $this->assertEquals('jane.smith@example.com', $jane->getEmail());
    }

    /** @test */
    function it_can_list_customers()
    {
        $customers = $this->api->listCustomers();

        $this->assertInternalType('array', $customers);
        foreach ($customers as $customer) {
            $this->assertInstanceOf('GoCardless\Pro\Models\Customer', $customer);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_customers()
    {
        $this->guardAgainstSmallNumberOfCustomerAccounts();

        $this->assertCount(3, $this->api->listCustomers(['limit' => 3]));
    }

    /** @depends it_can_create_a_customer */
    function test_it_can_get_a_single_customer(Customer $old)
    {
        $new = $this->api->getCustomer($old->getId());

        $this->assertInstanceOf('GoCardless\Pro\Models\Customer', $new);
        $this->assertEquals($old->toArray(), $new->toArray());
    }

    /** @depends it_can_create_a_customer */
    function test_it_can_create_a_customer_bank_account(Customer $customer)
    {
        $account = $this->get_customer_bank_account($customer);

        $account = $this->api->createCustomerBankAccount($account);

        $this->assertNotNull($account->getId());
        $this->assertNotNull($account->getCreatedAt());
        $this->assertNotNull($account->getAccountNumberEnding());
        $this->assertNotNull($account->getBankName());
        $this->assertNotNull($account->getCountryCode());
        $this->assertNotNull($account->getCurrency());
        $this->assertTrue($account->isEnabled());
        $this->assertFalse($account->isDisabled());

        return $account;
    }

    /** @test */
    function it_can_list_customer_bank_accounts()
    {
        $accounts = $this->api->listCustomerBankAccounts();

        $this->assertInternalType('array', $accounts);
        foreach ($accounts as $account) {
            $this->assertInstanceOf('GoCardless\Pro\Models\CustomerBankAccount', $account);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_customer_bank_accounts_returned()
    {
        $this->guardAgainstSmallNumberOfCustomerBankAccounts();

        $this->assertCount(3, $this->api->listCustomerBankAccounts(['limit' => 3]));
    }

    /**
     * @depends it_can_create_a_customer
     * @depends test_it_can_create_a_customer_bank_account
     */
    function test_it_can_return_customer_bank_accounts_for_a_specific_customer(
        Customer $customer,
        CustomerBankAccount $old
    ) {
        $accounts = $this->api->listCustomerBankAccounts(['customer' => $customer->getId()]);

        $this->assertCount(1, $accounts);
        $this->assertInternalType('array', $accounts);
        $this->assertInstanceOf('GoCardless\Pro\Models\CustomerBankAccount', $accounts[0]);
        $this->assertEquals($old->getId(), $accounts[0]->getId());
    }

    /** @depends test_it_can_create_a_customer_bank_account */
    function test_it_can_get_a_single_bank_account(CustomerBankAccount $old)
    {
        $new = $this->api->getCustomerBankAccount($old->getId());

        $this->assertInstanceOf('GoCardless\Pro\Models\CustomerBankAccount', $new);
        $this->assertEquals($old->toArray(), $new->toArray());
    }

    /** @test */
    function it_can_disable_a_bank_account()
    {
        $customer = $this->api->createCustomer($this->get_basic_customer());
        $account  = $this->get_customer_bank_account($customer);
        $account  = $this->api->createCustomerBankAccount($account);

        $account = $this->api->disableCustomerBankAccount($account->getId());

        $this->assertFalse($account->isEnabled());
        $this->assertTrue($account->isDisabled());
    }

    /**
     * @depends test_it_can_create_a_customer_bank_account
     * @depends test_it_can_get_a_single_creditor
     */
    function test_it_can_create_a_mandate(CustomerBankAccount $customerBankAccount, Creditor $creditor)
    {
        $mandate = new Mandate($customerBankAccount, $creditor);

        $mandate = $this->api->createMandate($mandate);

        $this->assertTrue($mandate->isPendingSubmission());
        $this->assertNotNull($mandate->getId());
        $this->assertNotNull($mandate->getCreatedAt());
        $this->assertNotNull($mandate->getReference());
        $this->assertNotNull($mandate->getNextPossibleChargeDate());

        return $mandate;
    }

    /** @test */
    function it_can_lists_mandates()
    {
        $mandates = $this->api->listMandates();

        $this->assertInternalType('array', $mandates);
        foreach ($mandates as $mandate) {
            $this->assertInstanceOf('GoCardless\Pro\Models\Mandate', $mandate);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_mandates()
    {
        $this->guardAgainstSmallNumberOfMandates();

        $this->assertCount(3, $this->api->listMandates(['limit' => 3]));
    }

    /** @depends test_it_can_create_a_mandate */
    function test_it_can_get_a_single_mandate(Mandate $old)
    {
        $new = $this->api->getMandate($old->getId());

        $this->assertEquals($old->toArray(), $new->toArray());
    }

    /** @depends test_it_can_create_a_mandate */
    function test_it_can_cancel_a_mandate(Mandate $mandate)
    {
        $mandate = $this->api->cancelMandate($mandate->getId());

        $this->assertTrue($mandate->isCancelled());
        $this->assertNull($mandate->getNextPossibleChargeDate());

        return $mandate;
    }

    /** @depends test_it_can_cancel_a_mandate */
    function test_it_can_reinstate_a_cancelled_mandate(Mandate $mandate)
    {
        $mandate = $this->api->reinstateMandate($mandate->getId());

        $this->assertFalse($mandate->isCancelled());
        $this->assertTrue($mandate->isPendingSubmission());
        $this->assertNotNull($mandate->getNextPossibleChargeDate());
    }

    /** @depends test_it_can_create_a_mandate */
    function test_it_can_create_a_payment(Mandate $mandate)
    {
        $payment = (new Payment())->collect(1000, 'GBP')->using($mandate);

        $payment = $this->api->createPayment($payment);

        $this->assertInstanceOf('GoCardless\Pro\Models\Payment', $payment);
        $this->assertNotNull($payment->getId());
        $this->assertNotNull($payment->getCreatedAt());
        $this->assertNotNull($payment->getChargeDate());
        $this->assertTrue($payment->isPendingSubmission());
        $this->assertSame(1000, $payment->getAmount());
        $this->assertSame('GBP', $payment->getCurrency());

        return $payment;
    }

    /** @depends test_it_can_create_a_payment */
    function test_it_can_get_a_single_payment(Payment $old)
    {
        $new = $this->api->getPayment($old->getId());

        $this->assertEquals($old->toArray(), $new->toArray());

        return $new;
    }

    /** @depends test_it_can_get_a_single_payment */
    function test_it_can_cancel_payments(Payment $payment)
    {
        $payment = $this->api->cancelPayment($payment->getId());

        $this->assertTrue($payment->isCancelled());

        return $payment;
    }

    /** @group Exceptions */
    function test_it_throws_an_exception_if_the_creditor_is_not_found()
    {
        $this->setExpectedException(
            'GoCardless\Pro\Exceptions\ResourceNotFoundException',
            'Resource not found at /creditors/1234',
            404
        );

        $this->api->getCreditor('1234');
    }

    /** @group Exceptions */
    function test_it_throws_an_exception_if_the_creditor_bank_account_is_not_found()
    {
        $this->setExpectedException(
            'GoCardless\Pro\Exceptions\ResourceNotFoundException',
            'Resource not found at /creditor_bank_accounts/1234',
            404
        );

        $this->api->getCreditorBankAccount('1234');
    }

    /** @group Exceptions */
    function test_it_throws_an_exception_if_the_customer_is_not_found()
    {
        $this->setExpectedException(
            'GoCardless\Pro\Exceptions\ResourceNotFoundException',
            'Resource not found at /customers/1234',
            404
        );

        $this->api->getCustomer('1234');
    }

    /** @group Exceptions */
    function test_it_throws_an_exception_if_the_customer_bank_account_is_not_found()
    {
        $this->setExpectedException(
            'GoCardless\Pro\Exceptions\ResourceNotFoundException',
            'Resource not found at /customer_bank_accounts/1234',
            404
        );

        $this->api->getCustomerBankAccount('1234');
    }

    /** @group Exceptions */
    function test_it_throws_an_exception_if_a_mandate_is_not_found()
    {
        $this->setExpectedException(
            'GoCardless\Pro\Exceptions\ResourceNotFoundException',
            'Resource not found at /mandates/1234',
            404
        );

        $this->api->getMandate('1234');
    }

    /** @group Exceptions */
    function test_it_throws_an_exception_if_a_payment_is_not_found()
    {
        $this->setExpectedException(
            'GoCardless\Pro\Exceptions\ResourceNotFoundException',
            'Resource not found at /payments/1234',
            404
        );

        $this->api->getPayment('1234');
    }

    /** @group Exceptions */
    function test_it_throws_validation_exception_on_validation_failed_api_error()
    {
        $this->setExpectedException('GoCardless\Pro\Exceptions\ValidationException');

        $this->api->createCustomer($this->get_invalid_customer());
    }

    /** @group Exceptions */
    function test_it_throws_version_not_found_exception_if_api_returns_this_error()
    {
        $this->setExpectedException(
            'GoCardless\Pro\Exceptions\VersionNotFoundException',
            'Version not found',
            400
        );

        $config = require __DIR__ . '/../config.php';

        $api = new Api(new Client, $config['accessToken'], '1970-01-01');

        $api->listCustomers();
    }

    /**
     * A simple guard to make sure the account has enough customers to limit.
     * In the future GoCardless will allow purging of account which will mean
     * we can create and clean up.
     */
    private function guardAgainstSmallNumberOfCustomerAccounts()
    {
        if (count($this->api->listCustomers()) < 5) {
            $this->markTestSkipped('Skipping test due to lack of customers in system. This test requires at least 5.');
        }
    }

    private function guardAgainstSmallNumberOfCustomerBankAccounts()
    {
        if (count($this->api->listCustomerBankAccounts()) < 5) {
            $this->markTestSkipped('Skipping test due to lack of customer bank accounts in system. This test requires at least 5.');
        }
    }

    private function guardAgainstSmallNumberOfCreditors()
    {
        if (count($this->api->listCreditors()) < 5) {
            $this->markTestSkipped('Skipping test due to lack of creditors in system. This test requires at least 5.');
        }
    }

    private function guardAgainstSmallNumberOfCreditorBankAccounts()
    {
        if (count($this->api->listCreditorBankAccounts()) < 5) {
            $this->markTestSkipped('Skipping test due to lack of creditor bank accounts in system. This test requires at least 5.');
        }
    }

    private function guardAgainstSmallNumberOfMandates()
    {
        if (count($this->api->listMandates()) < 5) {
            $this->markTestSkipped('Skipping test due to lack of mandates in system. This test requires at least 5.');
        }
    }
}
