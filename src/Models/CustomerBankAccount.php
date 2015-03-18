<?php namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\BankAccount;

class CustomerBankAccount extends BankAccount
{
    /** @var Customer */
    protected $customer;

    public function setOwner(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function toArray()
    {
        $account = array_filter(get_object_vars($this));

        if ($this->customer instanceof Customer)
        {
            unset($account['customer']);
            $account['links'] = [
                'customer' => $this->customer->getId()
            ];
        }

        return $account;
    }
}