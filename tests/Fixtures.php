<?php namespace GoCardless\Pro\Tests;

use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\CreditorBankAccount;
use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;

trait Fixtures
{
    /**
     * @return Customer
     */
    protected function get_basic_customer()
    {
        return Customer::fromArray($this->basic_customer_details());
    }

    /**
     * @return Customer
     */
    protected function get_full_customer()
    {
        return Customer::fromArray($this->full_customer_details());
    }

    /**
     * @return Customer
     */
    public function get_invalid_customer()
    {
        return $this->get_basic_customer()->setEmail('cake.com');
    }

    /**
     * @return Creditor
     */
    public function get_basic_creditor()
    {
        return Creditor::fromArray($this->basic_creditor_details());
    }

    /**
     * @return Creditor
     */
    public function get_full_creditor()
    {
        return Creditor::fromArray($this->full_creditor_details());
    }

    /**
     * @param Customer $customer
     *
     * @return CustomerBankAccount
     */
    public function get_customer_bank_account(Customer $customer = null)
    {
        $customer = $customer ?: $this->get_full_customer();
        $account  = CustomerBankAccount::fromArray($this->basic_bank_account_details());
        $account->setOwner($customer);

        return $account;
    }

    /**
     * @param Customer $customer
     *
     * @return CustomerBankAccount
     */
    public function get_customer_full_bank_account(Customer $customer = null)
    {
        $customer = $customer ?: $this->get_full_customer();
        $account  = CustomerBankAccount::fromArray($this->full_bank_account_details());
        $account->setOwner($customer);

        return $account;
    }

    /**
     * @param Creditor $creditor
     *
     * @return CreditorBankAccount
     */
    public function get_creditor_bank_account(Creditor $creditor = null)
    {
        $creditor = $creditor ?: $this->get_full_creditor();
        $account  = CreditorBankAccount::fromArray($this->basic_bank_account_details());

        $account->setAccountHolderName('Nude Wines')
            ->setOwner($creditor);

        return $account;
    }

    /**
     * @param Creditor $creditor
     *
     * @return CreditorBankAccount
     */
    public function get_creditor_full_bank_account(Creditor $creditor = null)
    {
        $creditor = $creditor ?: $this->get_full_creditor();
        $account  = CreditorBankAccount::fromArray($this->full_bank_account_details());

        $account->setAccountHolderName('Nude Wines')
            ->setOwner($creditor);

        return $account;
    }

    /**
     * @return array
     */
    public function basic_customer_details()
    {
        return [
            'given_name'    => 'John',
            'family_name'   => 'Doe',
            'email'         => 'john.doe@gmail.com',
            'address_line1' => '10 Downing Street',
            'city'          => 'London',
            'postal_code'   => 'SW1A 2AA',
            'country_code'  => 'GB',
        ];
    }

    /**
     * @return array
     */
    public function full_customer_details()
    {
        return array_merge($this->basic_customer_details(), [
            'id'            => 'CU123',
            'created_at'    => '2014-05-08T17:01:06.000Z',
            'address_line2' => 'Address Line 2',
            'address_line3' => 'Address Line 3',
            'region'        => 'Somewhere',
        ]);
    }

    /**
     * @return array
     */
    private function basic_creditor_details()
    {
        return [
            'name'          => 'The Wine Club',
            'address_line1' => '9 Acer Gardens',
            'city'          => 'Birmingham',
            'postal_code'   => 'B4 7NJ',
            'country_code'  => 'GB',
        ];
    }

    /**
     * @return array
     */
    private function full_creditor_details()
    {
        return array_merge($this->basic_creditor_details(), [
            'id'            => 'CR123',
            'created_at'    => '2014-05-27T12:43:17.000Z',
            'address_line2' => 'ul',
            'address_line3' => 'ul',
            'region'        => 'ul',
        ]);
    }

    /**
     * @return array
     */
    public function basic_bank_account_details()
    {
        return [
            'account_holder_name' => 'Mr John Doe',
            'account_number'      => '55779911',
            'branch_code'         => '200000',
            'country_code'        => 'GB',
        ];
    }

    /**
     * @return array
     */
    public function full_bank_account_details()
    {
        return array_merge($this->basic_bank_account_details(), [
            'id'                    => 'BA123',
            'created_at'            => '2014-05-08T17:01:06.000Z',
            'iban'                  => 'IBAN',
            'bank_code'             => 'BANK_CODE',
            'account_number_ending' => '11',
            'currency'              => 'GBP',
            'bank_name'             => 'BARCLAYS BANK PLC',
            'enabled'               => true,
        ]);
    }
}