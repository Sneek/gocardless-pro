<?php

namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Entity;
use GoCardless\Pro\Models\Traits\Factory;
use GoCardless\Pro\Models\Traits\Metadata;

class Payment extends Entity
{
    use Factory;
    use Metadata;

    /** @var string */
    private $charge_date;
    /** @var string */
    private $amount;
    /** @var string */
    private $currency;
    /** @var string */
    private $description;
    /** @var string */
    private $status;
    /** @var string */
    private $reference;
    /** @var string */
    private $amount_refunded;

    /** @var Mandate */
    private $mandate;

    /**
     * @param $amount
     * @param $currency
     *
     * @return $this
     */
    public function collect($amount, $currency)
    {
        return $this->setAmount($amount)->setCurrency($currency);
    }

    /**
     * @param Mandate $mandate
     *
     * @return Payment
     */
    public function using(Mandate $mandate)
    {
        return $this->setMandate($mandate);
    }

    /**
     * @param Mandate $mandate
     *
     * @return $this
     */
    public function setMandate(Mandate $mandate)
    {
        $this->mandate = $mandate;

        return $this;
    }

    /**
     * @param $date
     *
     * @return Payment
     */
    public function on($date)
    {
        return $this->setChargeDate($date);
    }

    /**
     * @return string
     */
    public function getChargeDate()
    {
        return $this->charge_date;
    }

    /**
     * @param $date
     *
     * @return $this
     */
    public function setChargeDate($date)
    {
        $this->charge_date = $date;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return intval($this->amount);
    }

    /**
     * @param $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

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
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPendingSubmission()
    {
        return $this->getStatus() === 'pending_submission';
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
    public function isSubmitted()
    {
        return $this->getStatus() === 'submitted';
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->getStatus() === 'confirmed';
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
    public function isChargedBack()
    {
        return $this->getStatus() === 'charged_back';
    }

    /**
     * @return bool
     */
    public function isPaidOut()
    {
        return $this->getStatus() === 'paid_out';
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return $this->getStatus() === 'cancelled';
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmountRefunded()
    {
        return intval($this->amount_refunded);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $payment = array_filter(get_object_vars($this));

        if ($this->mandate instanceof Mandate) {
            unset($payment['mandate']);
            $payment['links']['mandate'] = $this->mandate->getId();
        }

        return $payment;
    }
}
