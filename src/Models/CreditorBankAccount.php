<?php

namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\BankAccount;
use GoCardless\Pro\Models\Traits\Metadata;

class CreditorBankAccount extends BankAccount
{
    use Metadata;

    /** @var Creditor */
    protected $creditor;

    public function setOwner(Creditor $creditor)
    {
        $this->creditor = $creditor;

        return $this;
    }

    public function toArray()
    {
        $account = array_filter(get_object_vars($this));

        if ($this->creditor instanceof Creditor) {
            unset($account['creditor']);
            $account['links'] = [
                'creditor' => $this->creditor->getId()
            ];
        }

        return $account;
    }
}
