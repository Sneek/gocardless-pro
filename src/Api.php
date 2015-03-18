<?php namespace GoCardless\Pro;

use GoCardless\Pro\Exceptions\ValidationException;
use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\CreditorBankAccount;
use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Api
{
    const CREDITORS              = 'creditors';
    const CREDITOR_BANK_ACCOUNTS = 'creditor_bank_accounts';
    const CUSTOMERS              = 'customers';
    const CUSTOMER_BANK_ACCOUNTS = 'customer_bank_accounts';
    const MANDATES               = 'mandates';
    const PAYMENTS               = 'payments';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $environment;

    public function __construct(Client $client, $username, $password, $version, $environment = 'staging')
    {
        $this->client      = $client;
        $this->username    = $username;
        $this->password    = $password;
        $this->version     = $version;
        $this->environment = $environment === 'production' ? 'production' : 'staging';
    }

    /**
     * @param Creditor $creditor
     * @return Creditor
     */
    public function createCreditor(Creditor $creditor)
    {
        $response = $this->post(self::CREDITORS, $creditor->toArray());

        return Creditor::fromArray($response);
    }

    /**
     * @param Creditor $creditor
     * @return Creditor
     */
    public function updateCreditor(Creditor $creditor)
    {
        $response = $this->put(self::CREDITORS, $creditor->toArrayForUpdating(), $creditor->getId());

        return Creditor::fromArray($response);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function listCreditors($limit = 25)
    {
        $response = $this->get(self::CREDITORS, [
            'limit' => intval($limit)
        ]);

        return $this->buildCollection(new Creditor, $response);
    }

    /**
     * @param $id
     * @return Creditor
     */
    public function getCreditor($id)
    {
        $response = $this->get(self::CREDITORS, [], $id);

        return Creditor::fromArray($response);
    }

    /**
     * @param CreditorBankAccount $account
     * @return CreditorBankAccount
     */
    public function createCreditorBankAccount(CreditorBankAccount $account)
    {
        $response = $this->post(self::CREDITOR_BANK_ACCOUNTS, $account->toArray());

        return CreditorBankAccount::fromArray($response);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function listCreditorBankAccounts($limit = 25)
    {
        $response = $this->get(self::CREDITOR_BANK_ACCOUNTS, [
            'limit' => $limit,
        ]);

        return $this->buildCollection(new CreditorBankAccount, $response);
    }

    /**
     * @param $id
     * @return CreditorBankAccount
     */
    public function getCreditorBankAccount($id)
    {
        $response = $this->get(self::CREDITOR_BANK_ACCOUNTS, [], $id);

        return CreditorBankAccount::fromArray($response);
    }

    /**
     * @param $id
     * @return CreditorBankAccount
     */
    public function disableCreditorBankAccount($id)
    {
        $response = $this->post(self::CREDITOR_BANK_ACCOUNTS, [], $id . '/actions/disable');

        return CreditorBankAccount::fromArray($response);
    }

    /**
     * @param Customer $customer
     * @return Models\Customer
     */
    public function createCustomer(Customer $customer)
    {
        $response = $this->post(self::CUSTOMERS, $customer->toArray());

        return Customer::fromArray($response);
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    public function updateCustomer(Customer $customer)
    {
        $response = $this->put(self::CUSTOMERS, $customer->toArrayForUpdating(), $customer->getId());

        return Customer::fromArray($response);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function listCustomers($limit = 25)
    {
        $response = $this->get(self::CUSTOMERS, [
            'limit' => intval($limit),
        ]);

        return $this->buildCollection(new Customer, $response);
    }

    /**
     * @param $id
     * @return Customer
     */
    public function getCustomer($id)
    {
        $response = $this->get(self::CUSTOMERS, [], $id);

        return Customer::fromArray($response);
    }

    /**
     * @param CustomerBankAccount $account
     * @return CustomerBankAccount
     */
    public function createCustomerBankAccount(CustomerBankAccount $account)
    {
        $response = $this->post(self::CUSTOMER_BANK_ACCOUNTS, $account->toArray());

        return CustomerBankAccount::fromArray($response);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function listCustomerBankAccounts($limit = 25)
    {
        $response = $this->get(self::CUSTOMER_BANK_ACCOUNTS, [
            'limit' => intval($limit)
        ]);

        return $this->buildCollection(new CustomerBankAccount, $response);
    }

    /**
     * Return a single customer bank account
     *
     * @param $id
     * @return CustomerBankAccount
     */
    public function getCustomerBankAccount($id)
    {
        $response = $this->get(self::CUSTOMER_BANK_ACCOUNTS, [], $id);

        return CustomerBankAccount::fromArray($response);
    }

    /**
     * @param $id
     * @return CustomerBankAccount
     */
    public function disableCustomerBankAccount($id)
    {
        $response = $this->post(self::CUSTOMER_BANK_ACCOUNTS, [], $id . '/actions/disable');

        return CustomerBankAccount::fromArray($response);
    }

    /**
     * @param $endpoint
     * @param array $params
     * @param null $path
     * @return array
     */
    private function get($endpoint, $params = [], $path = null)
    {
        $response = $this->client->get($this->url($endpoint, $path), [
            'headers' => $this->headers(),
            'query'   => $params,
            'auth'    => [$this->username, $this->password]
        ])->json();

        return $response[$endpoint];
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param null $path
     * @return array
     */
    private function post($endpoint, $data, $path = null)
    {
        return $this->send('post', $endpoint, $data, $path);
    }

    /**
     * @param $endpoint
     * @param $data
     * @param null $path
     * @return array
     */
    public function put($endpoint, $data, $path = null)
    {
        return $this->send('put', $endpoint, $data, $path);
    }

    /**
     * @param $method
     * @param $endpoint
     * @param $data
     * @param $path
     * @return mixed
     * @throws ValidationException
     */
    private function send($method, $endpoint, $data, $path)
    {
        try
        {
            $response = $this->client->$method($this->url($endpoint, $path), [
                'headers' => $this->headers(),
                'json'    => [$endpoint => $data],
                'auth'    => [$this->username, $this->password]
            ])->json();
        }
        catch (BadResponseException $ex)
        {
            $this->handleBadResponseException($ex);
        }

        return $response[$endpoint];
    }

    /**
     * @param string $endpoint
     * @param string $path
     * @return string
     */
    private function url($endpoint, $path = null)
    {
        return $this->baseUrl() . $endpoint . ($path ? '/' . $path : '');
    }

    /**
     * @return string
     */
    private function baseUrl()
    {
        return $this->environment === 'staging'
            ? 'https://api-sandbox.gocardless.com/'
            : 'https://api.gocardless.com/';
    }

    /**
     * @return array
     */
    private function headers()
    {
        return [
            'GoCardless-Version' => $this->version,
            'Content-Type'       => 'application/json'
        ];
    }

    /**
     * @param object $model
     * @param array $response
     * @return array
     */
    private function buildCollection($model, $response)
    {
        $collection = [];

        foreach ($response as $details)
        {
            $collection[] = $model::fromArray($details);
        }

        return $collection;
    }

    /**
     * @param BadResponseException $ex
     * @throws ValidationException
     */
    private function handleBadResponseException(BadResponseException $ex)
    {
        $response = $ex->getResponse()->json();

        if ($response['error']['type'] === 'validation_failed')
        {
            throw new ValidationException(
                $response['error']['message'],
                $response['error']['errors']
            );
        }

        throw $ex;
    }
}