<?php namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Entity;
use GoCardless\Pro\Models\Traits\Factory;
use GoCardless\Pro\Models\Mandate;

class MandatePdf extends Entity
{
    use Factory;

    /**
     * @var string
     */
    protected $account_number;

    /**
     * @var string
     */
    protected $bank_code;

    /**
     * @var string
     */
    protected $bic;

    /**
     * @var string
     */
    protected $branch_code;

    /**
     * @var string
     */
    protected $country_code;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $mandate_reference;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $signature_date;

    /**
     * @var string
     */
    protected $mandate;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $expires_at;

    public function __construct(Mandate $mandate = null)
    {
        $this->mandate = $mandate;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * @param string $account_number Account number
     *
     * @return MandatePdf
     */
    public function setAccountNumber($account_number)
    {
        $this->account_number = $account_number;

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
     * @param string $bank_code Bank code
     *
     * @return MandatePdf
     */
    public function setBankCode($bank_code)
    {
        $this->bank_code = $bank_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $bic BIC
     *
     * @return MandatePdf
     */
    public function setBic($bic)
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branch_code;
    }

    /**
     * @param string $branch_code Branch Code
     *
     * @return MandatePdf
     */
    public function setBranchCode($branch_code)
    {
        $this->branch_code = $branch_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $country_code Country Code
     *
     * @return MandatePdf
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;

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
     * @param string $iban IBAN
     *
     * @return MandatePdf
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string
     */
    public function getMandateReference()
    {
        return $this->mark_reference;
    }

    /**
     * @param string $mandate_reference Mandate Reference
     *
     * @return MandatePdf
     */
    public function setMandateReference($mandate_reference)
    {
        $this->mark_reference = $mandate_reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->get_scheme;
    }

    /**
     * @param string $scheme Direct Debit Scheme
     *
     * @return MandatePdf
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
    public function getSignatureDate()
    {
        return $this->signature_date;
    }

    /**
     * @param string $signature_date Signature Date
     *
     * @return MandatePdf
     */
    public function setSignatureDate($signature_date)
    {
        $this->signature_date = $signature_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string (timestamp)
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $mandate = array_filter(get_object_vars($this));

        if ($this->mandate instanceof Mandate) {
            unset($mandate['mandate']);
            $mandate['links']['mandate'] = $this->mandate->getId();
        }

        return $mandate;
    }
}
