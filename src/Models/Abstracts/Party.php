<?php

namespace GoCardless\Pro\Models\Abstracts;

use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\Traits\Factory;

abstract class Party extends Entity
{
    use Factory;

    /** @var string */
    protected $address_line1;
    /** @var string */
    protected $address_line2;
    /** @var string */
    protected $address_line3;
    /** @var string */
    protected $city;
    /** @var string */
    protected $region;
    /** @var string */
    protected $postal_code;
    /** @var string */
    protected $country_code;

    /**
     * @param $street
     * @param $city
     * @param $postal_code
     * @param $country_code
     *
     * @return Customer
     */
    public function setAddress($street, $city, $postal_code, $country_code)
    {
        $this->setAddressLine1($street)->setCity($city)->setPostalCode($postal_code)->setCountryCode($country_code);

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->getAddressLine1();
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->address_line1;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->address_line2;
    }

    /**
     * @return string
     */
    public function getAddressLine3()
    {
        return $this->address_line3;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param $street
     *
     * @return Customer
     */
    public function setStreet($street)
    {
        return $this->setAddressLine1($street);
    }

    /**
     * @param $address_line1
     *
     * @return Customer
     */
    public function setAddressLine1($address_line1)
    {
        $this->address_line1 = $address_line1;

        return $this;
    }

    /**
     * @param $address_line2
     *
     * @return Customer
     */
    public function setAddressLine2($address_line2)
    {
        $this->address_line2 = $address_line2;

        return $this;
    }

    /**
     * @param $address_line3
     *
     * @return Customer
     */
    public function setAddressLine3($address_line3)
    {
        $this->address_line3 = $address_line3;

        return $this;
    }

    /**
     * @param $city
     *
     * @return Customer
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param $region
     *
     * @return Customer
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @param $postal_code
     *
     * @return Customer
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    /**
     * @param $country_code
     *
     * @return Customer
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @return array
     */
    public function toArrayForUpdating()
    {
        return array_diff_key($this->toArray(), array_flip(['id', 'created_at']));
    }
}