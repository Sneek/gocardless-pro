<?php namespace GoCardless\Pro;

use GoCardless\Pro\Exceptions\AuthenticationException;
use GoCardless\Pro\Exceptions\InvalidDocumentStructureException;
use GoCardless\Pro\Exceptions\InvalidStateException;
use GoCardless\Pro\Exceptions\ResourceNotFoundException;
use GoCardless\Pro\Exceptions\ValidationException;
use GoCardless\Pro\Exceptions\VersionNotFoundException;
use GoCardless\Pro\Models\Abstracts\Entity;
use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\CreditorBankAccount;
use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;
use GoCardless\Pro\Models\Mandate;
use GoCardless\Pro\Models\MandatePdf;
use GoCardless\Pro\Models\Payment;
use GoCardless\Pro\Models\RedirectFlow;
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
    const REDIRECT_FLOWS         = 'redirect_flows';
    const MANDATE_PDFS           = 'mandate_pdfs';
    const BANK_DETAILS_LOOKUPS   = 'bank_details_lookups';

    const SANDBOX_URL    = 'https://api-sandbox.gocardless.com/';
    const PRODUCTION_URL = 'https://api.gocardless.com/';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $environment;

    public function __construct(Client $client, $accessToken, $version, $environment = 'staging')
    {
        $this->client      = $client;
        $this->accessToken = $accessToken;
        $this->version     = $version;
        $this->environment = $environment === 'production' ? 'production' : 'staging';
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditors-create-a-creditor
     *
     * @param Creditor $creditor
     *
     * @return Creditor
     */
    public function createCreditor(Creditor $creditor)
    {
        $response = $this->post(self::CREDITORS, $creditor->toArray());

        return Creditor::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditors-update-a-creditor
     *
     * @param Creditor $creditor
     *
     * @return Creditor
     */
    public function updateCreditor(Creditor $creditor)
    {
        $response = $this->put(self::CREDITORS, $creditor->toArrayForUpdating(), $creditor->getId());

        return Creditor::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditors-list-creditors
     *
     * @param array $options
     *
     * @return array
     */
    public function listCreditors($options = [])
    {
        $response = $this->get(self::CREDITORS, $options);

        return $this->buildCollection(new Creditor, $response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditors-get-a-single-creditor
     *
     * @param $id
     *
     * @return Creditor
     */
    public function getCreditor($id)
    {
        $response = $this->get(self::CREDITORS, [], $id);

        return Creditor::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditor-bank-accounts-create-a-creditor-bank-account
     * @param CreditorBankAccount $account
     *
     * @return CreditorBankAccount
     */
    public function createCreditorBankAccount(CreditorBankAccount $account)
    {
        $response = $this->post(self::CREDITOR_BANK_ACCOUNTS, $account->toArray());

        return CreditorBankAccount::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditor-bank-accounts-list-creditor-bank-accounts
     * @param array $options
     *
     * @return array
     */
    public function listCreditorBankAccounts($options = [])
    {
        $response = $this->get(self::CREDITOR_BANK_ACCOUNTS, $options);

        return $this->buildCollection(new CreditorBankAccount, $response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditor-bank-accounts-get-a-single-creditor-bank-account
     *
     * @param $id
     *
     * @return CreditorBankAccount
     */
    public function getCreditorBankAccount($id)
    {
        $response = $this->get(self::CREDITOR_BANK_ACCOUNTS, [], $id);

        return CreditorBankAccount::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#creditor-bank-accounts-disable-a-creditor-bank-account
     *
     * @param $id
     *
     * @return CreditorBankAccount
     */
    public function disableCreditorBankAccount($id)
    {
        $response = $this->post(self::CREDITOR_BANK_ACCOUNTS, [], $id . '/actions/disable');

        return CreditorBankAccount::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#customers-create-a-customer
     *
     * @param Customer $customer
     *
     * @return Models\Customer
     */
    public function createCustomer(Customer $customer)
    {
        $response = $this->post(self::CUSTOMERS, $customer->toArray());

        return Customer::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#customers-update-a-customer
     * @param Customer $customer
     *
     * @return Customer
     */
    public function updateCustomer(Customer $customer)
    {
        $response = $this->put(self::CUSTOMERS, $customer->toArrayForUpdating(), $customer->getId());

        return Customer::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#customers-list-customers
     *
     * @param array $options
     *
     * @return array
     */
    public function listCustomers($options = [])
    {
        $response = $this->get(self::CUSTOMERS, $options);

        return $this->buildCollection(new Customer, $response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#customers-get-a-single-customer
     *
     * @param $id
     *
     * @return Customer
     */
    public function getCustomer($id)
    {
        $response = $this->get(self::CUSTOMERS, [], $id);

        return Customer::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#customer-bank-accounts-create-a-customer-bank-account
     *
     * @param CustomerBankAccount $account
     *
     * @return CustomerBankAccount
     */
    public function createCustomerBankAccount(CustomerBankAccount $account)
    {
        $response = $this->post(self::CUSTOMER_BANK_ACCOUNTS, $account->toArray());

        return CustomerBankAccount::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#customer-bank-accounts-list-customer-bank-accounts
     *
     * @param array $options
     *
     * @return array
     */
    public function listCustomerBankAccounts($options = [])
    {
        $response = $this->get(self::CUSTOMER_BANK_ACCOUNTS, $options);

        return $this->buildCollection(new CustomerBankAccount, $response);
    }

    /**
     * Return a single customer bank account
     *
     * @see https://developer.gocardless.com/pro/#customer-bank-accounts-get-a-single-customer-bank-account
     *
     * @param $id
     *
     * @return CustomerBankAccount
     */
    public function getCustomerBankAccount($id)
    {
        $response = $this->get(self::CUSTOMER_BANK_ACCOUNTS, [], $id);

        return CustomerBankAccount::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#customer-bank-accounts-disable-a-customer-bank-account
     *
     * @param $id
     *
     * @return CustomerBankAccount
     */
    public function disableCustomerBankAccount($id)
    {
        $response = $this->post(self::CUSTOMER_BANK_ACCOUNTS, [], $id . '/actions/disable');

        return CustomerBankAccount::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#api-endpoints-mandates
     *
     * @param Mandate $mandate
     *
     * @return Mandate $mandate
     */
    public function createMandate(Mandate $mandate)
    {
        $response = $this->post(self::MANDATES, $mandate->toArray());

        return Mandate::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#mandates-list-mandates
     *
     * @param array $options
     *
     * @return array
     */
    public function listMandates($options = [])
    {
        $response = $this->get(self::MANDATES, $options);

        return $this->buildCollection(new Mandate, $response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#mandates-get-a-single-mandate
     *
     * @param $id
     *
     * @return Mandate
     */
    public function getMandate($id)
    {
        $response = $this->get(self::MANDATES, [], $id);

        return Mandate::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#mandates-cancel-a-mandate
     *
     * @param $id
     *
     * @return Mandate
     */
    public function cancelMandate($id)
    {
        $response = $this->post(self::MANDATES, [], $id . '/actions/cancel');

        return Mandate::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#mandates-reinstate-a-mandate
     *
     * @param $id
     *
     * @return Mandate
     */
    public function reinstateMandate($id)
    {
        $response = $this->post(self::MANDATES, [], $id . '/actions/reinstate');

        return Mandate::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#payments-create-a-payment
     *
     * @param Payment $payment
     *
     * @return Payment
     */
    public function createPayment(Payment $payment)
    {
        $response = $this->post(self::PAYMENTS, $payment->toArray());

        return Payment::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#payments-get-a-single-payment
     *
     * @param $id
     *
     * @return Payment
     */
    public function getPayment($id)
    {
        $response = $this->get(self::PAYMENTS, [], $id);

        return Payment::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#payments-cancel-a-payment
     *
     * @param $id
     *
     * @return Payment
     */
    public function cancelPayment($id)
    {
        $response = $this->post(self::PAYMENTS, [], $id . '/actions/cancel');

        return Payment::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#payments-retry-a-payment
     *
     * @param $id
     *
     * @return Payment
     */
    public function retryPayment($id)
    {
        $response = $this->post(self::PAYMENTS, [], $id . '/actions/retry');

        return Payment::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#redirect-flows-create-a-redirect-flow
     *
     * @param RedirectFlow $redirectFlow Redirect Flow Entity
     *
     * @return RedirectFlow
     */
    public function createRedirectFlow(RedirectFlow $redirectFlow)
    {
        $response = $this->post(self::REDIRECT_FLOWS, $redirectFlow->toArray());

        return RedirectFlow::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#redirect-flows-get-a-single-redirect-flow
     *
     * @param string $id Redirect Flow ID
     *
     * @return RedirectFlow
     */
    public function getRedirectFlow($id)
    {
        $response = $this->get(self::REDIRECT_FLOWS, [], $id);

        return RedirectFlow::fromArray($response);
    }

    /**
     * @see https://developer.gocardless.com/pro/#redirect-flows-complete-a-redirect-flow
     *
     * @param string $id           Redirect Flow ID
     * @param string $sessionToken Session token used to create the flow
     *
     * @return RedirectFlow
     */
    public function completeRedirectFlow($id, $sessionToken)
    {
        try {
            $response = $this->client->post(
                $this->url(self::REDIRECT_FLOWS, $id . '/actions/complete'), [
                    'headers' => $this->headers(),
                    'json' => ['data' => ['session_token' => $sessionToken]]
                ]
            )->json();

        } catch (BadResponseException $ex) {
            $this->handleBadResponseException($ex);
        }

        return RedirectFlow::fromArray($response[self::REDIRECT_FLOWS]);
    }

    /**
     * Get Mandate PDF
     *
     * Generates a PDF mandate and returns its temporary URL.
     *
     * @see https://developer.gocardless.com/pro/#mandate-pdfs-create-a-mandate-pdf
     *
     * @param MandatePdf $mandatePdf MandatePdf model
     *
     * @return MandatePdf
     */
    public function createMandatePdf(MandatePdf $mandatePdf)
    {
        $response = $this->post(self::MANDATE_PDFS, $mandatePdf->toArray());

        return MandatePdf::fromArray($response);
    }

    /**
     * Look up the name and reachability of a bank.
     *
     * @see https://developer.gocardless.com/pro/#helper-endpoints-bank-details-lookups
     *
     * @param CustomerBankAccount $account Customer Bank Account
     *
     * @return array
     */
    public function lookupBankDetails(CustomerBankAccount $account)
    {
        $request = ['iban' => $account->getIban()];

        if (!$account->hasIban()) {
            $request = [
                'account_number' => $account->getAccountNumber(),
                'branch_code'    => $account->getBranchCode(),
                'country_code'   => $account->getCountryCode()
            ];
        }

        return $this->post(self::BANK_DETAILS_LOOKUPS, $request);
    }

    /**
     * Parses a date string from GoCardless and returns a DateTime
     *
     * @param string $date Date in go cardless format
     *
     * @return \DateTime
     */
    public function parseDate($date)
    {
        $timezone   = new \DateTimeZone('UTC');

        return \DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $date, $timezone);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @param string $path
     *
     * @return array
     */
    private function get($endpoint, $params = [], $path = null)
    {
        try {
            $response = $this->client->get($this->url($endpoint, $path), [
                'headers' => $this->headers(),
                'query'   => $params
            ])->json();
        } catch (BadResponseException $ex) {
            $this->handleBadResponseException($ex);
        }

        return $response[$endpoint];
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param string $path
     *
     * @return array
     */
    private function post($endpoint, $data = [], $path = null)
    {
        return $this->send('post', $endpoint, $data, $path);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param string $path
     *
     * @return array
     */
    private function put($endpoint, $data = [], $path = null)
    {
        return $this->send('put', $endpoint, $data, $path);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @param string $path
     *
     * @return mixed
     *
     * @throws ValidationException
     */
    private function send($method, $endpoint, $data = [], $path)
    {
        try {
            $payload = $data ? [$endpoint => $data] : null;

            $response = $this->client->$method($this->url($endpoint, $path), [
                'headers' => $this->headers(),
                'json'    => $payload
            ])->json();
        } catch (BadResponseException $ex) {
            $this->handleBadResponseException($ex);
        }

        return $response[$endpoint];
    }

    /**
     * @param string $endpoint
     * @param string $path
     *
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
            ? self::SANDBOX_URL
            : self::PRODUCTION_URL;
    }

    /**
     * @param array $additional
     * @return array
     */
    private function headers($additional = [])
    {
        return array_merge([
            'GoCardless-Version' => $this->version,
            'Content-Type'       => 'application/json',
            'Authorization'      => sprintf('Bearer %s', $this->accessToken)
        ], $additional);
    }

    /**
     * @param Entity $model
     * @param array $response
     *
     * @return array
     */
    private function buildCollection(Entity $model, array $response)
    {
        $collection = [];

        foreach ($response as $details) {
            $collection[] = $model::fromArray($details);
        }

        return $collection;
    }

    /**
     * @param BadResponseException $ex
     *
     * @throws InvalidStateException
     * @throws ResourceNotFoundException
     * @throws ValidationException
     * @throws VersionNotFoundException
     * @throws InvalidDocumentStructureException
     */
    private function handleBadResponseException(BadResponseException $ex)
    {
        $response = $ex->getResponse()->json();

        switch ($response['error']['type']) {
            case 'invalid_state' :
                throw new InvalidStateException(
                    $response['error']['message'],
                    $response['error']['code'],
                    $response['error']['errors']
                );

            case 'validation_failed' :
                $this->handleValidationFailedErrors($response);

            case 'invalid_api_usage' :
                $this->handleInvalidApiUsage($ex, $response);
        }

        throw $ex;
    }

    /**
     * @param array $response
     *
     * @throws ValidationException
     */
    private function handleValidationFailedErrors(array $response)
    {
        throw new ValidationException(
            $response['error']['message'],
            $response['error']['errors']
        );
    }

    /**
     * @param BadResponseException $ex
     * @param array $response
     *
     * @throws AuthenticationException
     * @throws InvalidDocumentStructureException
     * @throws ResourceNotFoundException
     * @throws VersionNotFoundException
     */
    private function handleInvalidApiUsage(BadResponseException $ex, array $response)
    {
        switch ($response['error']['errors'][0]['reason']) {
            case 'resource_not_found' :
                throw new ResourceNotFoundException(
                    sprintf('Resource not found at %s', $ex->getRequest()->getResource()),
                    $ex->getCode()
                );
            case 'version_not_found' :
                throw new VersionNotFoundException(
                    'Version not found',
                    $ex->getCode()
                );
            case 'invalid_document_structure':
                throw new InvalidDocumentStructureException(
                    $response['error']['message'],
                    $ex->getCode()
                );
            case 'access_token_not_found' :
            case 'insufficient_permissions' :
                throw new AuthenticationException(
                    $response['error']['message'],
                    $ex->getCode()
                );
        }
    }
}
