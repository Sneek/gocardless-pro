<?php namespace GoCardless\Pro\Models\Abstracts;

use GoCardless\Pro\Models\Traits\Factory;

abstract class BankAccount extends Entity
{
    use Factory;

    /** @var string */
    protected $account_number_ending;
    /** @var string */
    protected $bank_name;
    /** @var bool */
    protected $enabled;
    /** @var string */
    protected $account_holder_name;
    /** @var string */
    protected $account_number;
    /** @var string */
    protected $branch_code;
    /** @var string */
    protected $bank_code;
    /** @var string */
    protected $iban;
    /** @var string */
    protected $country_code;
    /** @var string */
    protected $currency;

    /**
     * @param $sort_code
     * @return $this
     */
    public function setSortCode($sort_code)
    {
        return $this->setBranchCode($sort_code);
    }

    /**
     * @return string
     */
    public function getAccountNumberEnding()
    {
        return $this->account_number_ending;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return !$this->isEnabled();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->enabled;
    }

    /**
     * @return string
     */
    public function getAccountHolderName()
    {
        return $this->account_holder_name;
    }

    /**
     * @param $account_holder_name
     * @return $this
     */
    public function setAccountHolderName($account_holder_name)
    {
        $this->account_holder_name = $account_holder_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * @param $account_number
     * @return $this
     */
    public function setAccountNumber($account_number)
    {
        $this->account_number = $account_number;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortCode()
    {
        return $this->getBranchCode();
    }

    /**
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branch_code;
    }

    /**
     * @param $branch_code
     * @return $this
     */
    public function setBranchCode($branch_code)
    {
        $this->branch_code = $branch_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bank_code;
    }

    /**
     * @param $bank_code
     * @return $this
     */
    public function setBankCode($bank_code)
    {
        $this->bank_code = $bank_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param $iban
     * @return $this
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasIban()
    {
        return !!$this->iban;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param $country_code
     * @return $this
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }
}
