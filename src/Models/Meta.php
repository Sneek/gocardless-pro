<?php namespace GoCardless\Pro\Models;

use InvalidArgumentException;

class Meta
{
    /** @var array */
    private $attributes = [];

    public function __construct($meta = [])
    {
        foreach ($meta as $key => $value) {
            $this->add($key, $value);
        }
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function add($key, $value)
    {
        $this->guardAgainstMaxKeyLength($key);
        $this->guardAgainstMaxValueLength($value);

        $this->attributes[$key] = $value;

        $this->guardAgainstMaxNumberOfMetaAttribute();

        return $this;
    }

    public function get($key)
    {
        if ($this->exists($key)) {
            return $this->attributes[$key];
        }

        return null;
    }

    /**
     * @param $key
     *
     * @return $this
     */
    public function remove($key)
    {
        if ($this->exists($key)) {
            unset($this->attributes[$key]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->attributes;
    }

    public function exists($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    private function guardAgainstMaxNumberOfMetaAttribute()
    {
        if (count($this->attributes) > 3) {
            throw new InvalidArgumentException('GoCardless only allows 3 meta key / value pairs');
        }
    }

    /**
     * @param $key
     */
    private function guardAgainstMaxKeyLength($key)
    {
        if (mb_strlen($key) > 50) {
            throw new InvalidArgumentException('The supplied key is too long for go cardless, maximum allowed is 50. Key: ' . $key);
        }
    }

    /**
     * @param $value
     */
    private function guardAgainstMaxValueLength($value)
    {
        if (mb_strlen($value) > 200) {
            throw new InvalidArgumentException('The supplied value is too long for go cardless, maximum allowed is 200.  Value: ' . $value);
        }
    }
}