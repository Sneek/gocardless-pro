<?php namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Entity;
use GoCardless\Pro\Models\Traits\Factory;

class Mandate extends Entity
{
    use Factory;

    /**
     * @var CustomerBankAccount
     */
    protected $customer_bank_account;

    /**
     * @var CreditorBankAccount
     */
    protected $creditor_bank_account;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $next_possible_charge_date;

    public function __construct(CustomerBankAccount $customer_bank_account = null, CreditorBankAccount $creditor_bank_account = null)
    {
        $this->customer_bank_account = $customer_bank_account;
        $this->creditor_bank_account = $creditor_bank_account;
        $this->useBacs();
    }

    /**
     * @param CustomerBankAccount $account
     * @return $this
     */
    public function setCustomerBankAccount(CustomerBankAccount $account)
    {
        $this->customer_bank_account = $account;

        return $this;
    }

    /**
     * @param CreditorBankAccount $account
     * @return $this
     */
    public function setCreditorBankAccount(CreditorBankAccount $account)
    {
        $this->creditor_bank_account = $account;

        return $this;
    }

    /**
     * Set to use the bacs scheme for the mandate
     *
     * @return Mandate
     */
    public function useBacs()
    {
        return $this->setScheme('bacs');
    }

    /**
     * Set to use the sepa core scheme for the mandate
     *
     * @return Mandate
     */
    public function useSepaCore()
    {
        return $this->setScheme('sepa_core');
    }

    /**
     * @param $scheme
     * @return $this
     */
    protected function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getNextPossibleChargeDate()
    {
        return $this->next_possible_charge_date;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $mandate = array_filter(get_object_vars($this));

        if ($this->customer_bank_account instanceof CustomerBankAccount)
        {
            unset($mandate['customer_bank_account']);
            $mandate['links']['customer_bank_account'] = $this->customer_bank_account->getId();
        }

        if ($this->creditor_bank_account instanceof CreditorBankAccount)
        {
            unset($mandate['creditor_bank_account']);
            $mandate['links']['creditor_bank_account'] = $this->creditor_bank_account->getId();
        }

        return $mandate;
    }
}