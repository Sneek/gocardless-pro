<?php

namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Entity;
use GoCardless\Pro\Models\Traits\Factory;
use GoCardless\Pro\Models\Traits\Metadata;

class Refund extends Entity
{
    use Factory;
    use Metadata;

    /** @var string */
    private $amount;

    /** @var string */
    private $reference;

    /** @var string */
    private $currency;

    /** @var string */
    private $total_amount_confirmation;

    /** @var Payment */
    protected $payment;

    /**
     * @param Payment $payment
     *
     * @return Refund
     */
    public function of(Payment $payment)
    {
        return $this->setPayment($payment);
    }

    /**
     * @param $amount
     *
     * @return Refund
     */
    public function returning($amount)
    {
        return $this->setAmount($amount);
    }

    /**
     * @param $amount
     *
     * @return Refund
     */
    public function totalling($amount)
    {
        return $this->setTotalAmountConfirmation($amount);
    }

    /**
     * @param $amount
     *
     * @return Refund
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param $currency
     *
     * @return Refund
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param $amount
     *
     * @return Refund
     */
    public function setTotalAmountConfirmation($amount)
    {
        $this->total_amount_confirmation = $amount;

        return $this;
    }

    /**
     * @param $reference
     *
     * @return Refund
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @param Payment $payment
     *
     * @return Refund
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;

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
     * @return int
     */
    public function getTotalAmountConfirmation()
    {
        return intval($this->total_amount_confirmation);
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $refund = array_filter(get_object_vars($this));

        if ($this->payment instanceof Payment) {
            unset($refund['payment']);
            $refund['links']['payment'] = $this->payment->getId();
        }

        return $refund;
    }
}