<?php namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Party;

class Customer extends Party
{
    /** @var string */
    protected $email;
    /** @var string */
    protected $given_name;
    /** @var string */
    protected $family_name;

    /**
     * @param $forename
     * @param $surname
     * @return Customer
     */
    public function setFullName($forename, $surname)
    {
        $this->setGivenName($forename)->setFamilyName($surname);

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getForename()
    {
        return $this->getGivenName();
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
        return $this->given_name;
    }

    /**
     * @param $given_name
     * @return Customer
     */
    public function setGivenName($given_name)
    {
        $this->given_name = $given_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getGivenName();
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->getFamilyName();
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
        return $this->family_name;
    }

    /**
     * @param $family_name
     * @return Customer
     */
    public function setFamilyName($family_name)
    {
        $this->family_name = $family_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getFamilyName();
    }

    /**
     * @param $name
     * @return Customer
     */
    public function setForename($name)
    {
        return $this->setGivenName($name);
    }

    /**
     * @param $name
     * @return Customer
     */
    public function setFirstName($name)
    {
        return $this->setGivenName($name);
    }

    /**
     * @param $surname
     * @return Customer
     */
    public function setSurname($surname)
    {
        return $this->setFamilyName($surname);
    }

    /**
     * @param $last_name
     * @return Customer
     */
    public function setLastName($last_name)
    {
        return $this->setFamilyName($last_name);
    }
}