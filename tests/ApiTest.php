<?php namespace GoCardless\Pro\Tests;

use GoCardless\Pro\Api;
use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\CreditorBankAccount;
use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;
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

        $this->api = new Api(new Client, $config['username'], $config['password'], $config['version']);
    }

    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf('GoCardless\Pro\Api', $this->api);
        $this->assertAttributeInstanceOf('GuzzleHttp\Client', 'client', $this->api);
        $this->assertAttributeNotEmpty('username', $this->api);
        $this->assertAttributeNotEmpty('password', $this->api);
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
        foreach ($creditors as $creditor)
        {
            $this->assertInstanceOf('GoCardless\Pro\Models\Creditor', $creditor);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_creditors()
    {
        $this->guardAgainstSmallNumberOfCreditors();

        $this->assertCount(3, $this->api->listCreditors(3));
    }

    /** @depends it_can_create_a_creditor */
    function test_it_can_get_a_single_creditor(Creditor $old)
    {
        $new = $this->api->getCreditor($old->getId());

        $this->assertInstanceOf('GoCardless\Pro\Models\Creditor', $new);
        $this->assertEquals($old->toArray(), $new->toArray());
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
        foreach ($accounts as $account)
        {
            $this->assertInstanceOf('GoCardless\Pro\Models\CreditorBankAccount', $account);
        }
    }

    /** @test */
    function it_can_limit_creditor_bank_accounts()
    {
        $this->guardAgainstSmallNumberOfCreditorBankAccounts();

        $this->assertCount(3, $this->api->listCreditorBankAccounts(3));
    }

    /** @depends test_it_can_create_creditor_bank_account */
    function test_it_can_get_a_creditor_bank_account(CreditorBankAccount $old)
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
        foreach ($customers as $customer)
        {
            $this->assertInstanceOf('GoCardless\Pro\Models\Customer', $customer);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_returned_customers()
    {
        $this->guardAgainstSmallNumberOfCustomerAccounts();

        $this->assertCount(3, $this->api->listCustomers(3));
    }

    /** @depends it_can_create_a_customer */
    function test_it_can_find_a_single_customer(Customer $old)
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
        foreach ($accounts as $account)
        {
            $this->assertInstanceOf('GoCardless\Pro\Models\CustomerBankAccount', $account);
        }
    }

    /** @test */
    function it_can_limit_the_number_of_customer_bank_accounts_returned()
    {
        $this->guardAgainstSmallNumberOfCustomerBankAccounts();

        $this->assertCount(3, $this->api->listCustomerBankAccounts(3));
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
        $account = $this->get_customer_bank_account($customer);
        $account = $this->api->createCustomerBankAccount($account);

        $account = $this->api->disableCustomerBankAccount($account->getId());

        $this->assertFalse($account->isEnabled());
        $this->assertTrue($account->isDisabled());
    }

    /** @test */
    function it_throws_validation_exception_on_validation_failed_api_error()
    {
        $this->setExpectedException('GoCardless\Pro\Exceptions\ValidationException');

        $this->api->createCustomer($this->get_invalid_customer());
    }

    /**
     * A simple guard to make sure the account has enough customers to limit.
     * In the future GoCardless will allow purging of account which will mean
     * we can create and clean up.
     */
    private function guardAgainstSmallNumberOfCustomerAccounts()
    {
        if (count($this->api->listCustomers()) < 5)
        {
            $this->markTestSkipped('Skipping test due to lack of customers in system. This test requires at least 5.');
        }
    }

    private function guardAgainstSmallNumberOfCustomerBankAccounts()
    {
        if (count($this->api->listCustomerBankAccounts()) < 5)
        {
            $this->markTestSkipped('Skipping test due to lack of customer bank accounts in system. This test requires at least 5.');
        }
    }

    private function guardAgainstSmallNumberOfCreditors()
    {
        if (count($this->api->listCreditors()) < 5)
        {
            $this->markTestSkipped('Skipping test due to lack of creditors in system. This test requires at least 5.');
        }
    }

    private function guardAgainstSmallNumberOfCreditorBankAccounts()
    {
        if (count($this->api->listCreditorBankAccounts()) < 5)
        {
            $this->markTestSkipped('Skipping test due to lack of creditor bank accounts in system. This test requires at least 5.');
        }
    }
}
