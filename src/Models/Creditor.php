<?php namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Party;
use GoCardless\Pro\Models\Traits\Factory;

class Creditor extends Party
{
    use Factory;

    /** @var string */
    protected $name;

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
}