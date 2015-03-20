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
     * @var Creditor
     */
    protected $creditor;

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

    public function __construct(CustomerBankAccount $customer_bank_account = null, Creditor $creditor = null)
    {
        $this->setCustomerBankAccount($customer_bank_account);
        $this->setCreditor($creditor);
        $this->useBacs();
    }

    /**
     * @param CustomerBankAccount $account
     * @return $this
     */
    public function setCustomerBankAccount(CustomerBankAccount $account = null)
    {
        $this->customer_bank_account = $account;

        return $this;
    }

    /**
     * @param Creditor $creditor
     * @return $this
     */
    public function setCreditor(Creditor $creditor = null)
    {
        $this->creditor = $creditor;

        return $this;
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
     * @return bool
     */
    public function isPendingSubmission()
    {
        return $this->getStatus() === 'pending_submission';
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->getStatus() === 'submitted';
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->getStatus() === 'active';
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return $this->getStatus() === 'failed';
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return $this->getStatus() === 'cancelled';
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->getStatus() === 'expired';
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return bool
     */
    public function isBacs()
    {
        return $this->getScheme() === 'bacs';
    }

    /**
     * @return bool
     */
    public function isSepaCore()
    {
        return $this->getScheme() === 'sepa_core';
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

        if ($this->customer_bank_account instanceof CustomerBankAccount) {
            unset($mandate['customer_bank_account']);
            $mandate['links']['customer_bank_account'] = $this->customer_bank_account->getId();
        }

        if ($this->creditor instanceof Creditor) {
            unset($mandate['creditor']);
            $mandate['links']['creditor'] = $this->creditor->getId();
        }

        return $mandate;
    }
}