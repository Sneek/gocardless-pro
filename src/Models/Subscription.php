<?php namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Entity;
use GoCardless\Pro\Models\Traits\Factory;
use GoCardless\Pro\Models\Traits\Metadata;

class Subscription extends Entity
{
    use Factory;
    use Metadata;

    /** @var string */
    private $amount;
    /** @var integer */
    private $count;
    /** @var string */
    private $currency;
    /** @var integer */
    private $day_of_month;
    /** @var string */
    private $end_at;
    /** @var integer */
    private $interval;
    /** @var string */
    private $interval_unit;
    /** @var string */
    private $month;
    /** @var string */
    private $name;
    /** @var string */
    private $payment_reference;
    /** @var string */
    private $start_at;
    /** @var string */
    private $status;
    /** @var array */
    private $upcoming_payments = [];
    /** @var Mandate */
    private $mandate;

    /**
     * @param $amount
     * @param $currency
     * @return $this
     */
    public function collect($amount, $currency)
    {
        return $this->setAmount($amount)->setCurrency($currency);
    }

    /**
     * @param $date
     * @return Subscription
     */
    public function from($date)
    {
        return $this->setStartAt($date);
    }

    /**
     * @param $date
     * @return Subscription
     */
    public function until($date)
    {
        return $this->setEndAt($date);
    }

    /**
     * @return Subscription
     */
    public function onLastDayOfMonth()
    {
        return $this->setDayOfMonth(-1);
    }

    /*public function on($dayOfMonth, $month = null)
    {
        //helper for uk months
        $months = [1 => 'january', 'february', 'march'. 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        if (!is_string($month) || !in_array(strtolower($month), $months)) {
            if (!is_numeric($month) || $month < 1 || $month > 12 ) {
                //default month as next month
                $month = date('n');
            }
            $month = $months[intval($month)];
        }

        return $this->setDayOfMonth($dayOfMonth)->setMonth($month);
    }*/

    /**
     * @param $interval
     * @param $unit
     * @return $this
     */
    public function every($interval, $unit)
    {
        return $this->setInterval($interval)->setIntervalUnit($unit);
    }

    /**
     * @param null $forNumberOfWeeks
     * @return Subscription
     */
    public function everyWeek($forNumberOfWeeks = null)
    {
        if ($forNumberOfWeeks) {
            $this->setCount($forNumberOfWeeks);
        }

        return $this->every(1, 'weekly');
    }

    /**
     * @param null $forNumberOfMonths
     * @return Subscription
     */
    public function everyMonth($forNumberOfMonths = null)
    {
        if ($forNumberOfMonths) {
            $this->setCount($forNumberOfMonths);
        }

        return $this->every(1, 'monthly');
    }

    /**
     * @param null $forNumberOfYears
     * @return Subscription
     */
    public function everyYear($forNumberOfYears = null)
    {
        if ($forNumberOfYears) {
            $this->setCount($forNumberOfYears);
        }

        return $this->every(1, 'yearly');
    }

    /**
     * @param Mandate $mandate
     * @return Subscription
     */
    public function using(Mandate $mandate)
    {
        return $this->setMandate($mandate);
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
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;
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

    /**
     * @return int
     */
    public function getDayOfMonth()
    {
        return intval($this->day_of_month);
    }

    /**
     * @param $day
     * @return $this
     */
    public function setDayOfMonth($day)
    {
        $this->day_of_month = $day;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndAt()
    {
        return $this->end_at;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setEndAt($date)
    {
        $this->end_at = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return intval($this->interval);
    }

    /**
     * @param $interval
     * @return $this
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * @return string
     */
    public function getIntervalUnit()
    {
        return $this->interval_unit;
    }

    /**
     * @param $unit
     * @return $this
     */
    public function setIntervalUnit($unit)
    {
        $this->interval_unit = $unit;
        return $this;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param $month
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = strtolower($month);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentReference()
    {
        return $this->payment_reference;
    }

    /**
     * @param $ref
     * @return $this
     */
    public function setPaymentReference($ref)
    {
        $this->payment_reference = $ref;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartAt()
    {
        return $this->start_at;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setStartAt($date)
    {
        $this->start_at = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getUpcomingPayments()
    {
        return $this->upcoming_payments;
    }

    /**
     * @param Mandate $mandate
     * @return $this
     */
    public function setMandate(Mandate $mandate)
    {
        $this->mandate = $mandate;

        return $this;
    }

    public function toArrayForUpdating()
    {
        return [
            'name' => $this->getName(),
            'payment_reference' => $this->getPaymentReference(),
            'metadata' => $this->getMetadata()
        ];
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
    public function isCancelled()
    {
        return $this->getStatus() === 'cancelled';
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $subscription = array_filter(get_object_vars($this));

        if ($this->mandate instanceof Mandate) {
            unset($subscription['mandate']);
            $subscription['links']['mandate'] = $this->mandate->getId();
        }

        return $subscription;
    }

}