<?php

namespace GoCardless\Pro\Models\Traits;

/**
 * Allows an Entity to implement metadata
 */
trait Metadata
{
    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * Sets an entire metadata array
     *
     * @param array $metadata Metadata to set
     *
     * @return Entity
     */
    public function setMetadata(array $metadata)
    {
        foreach ($metadata as $key => $val) {
            $this->addMetadata($key, $val);
        }

        return $this;
    }

    /**
     * Get the current metadata
     *
     * Optionally specify a single key to retrieve
     *
     * @return array
     */
    public function getMetadata($key = null)
    {
        if (!$key) {
            return $this->metadata;
        }

        if (!isset($this->metadata[$key])) {
            return null;
        }

        return $this->metadata[$key];
    }

    /**
     * Check if metadata key exists
     *
     * @param string $key Meta key
     *
     * @return boolean
     */
    public function metadataExists($key)
    {
        return isset($this->metadata[$key]);
    }

    /**
     * Add an item to the metadata
     *
     * @param string $key Meta key
     * @param string $val Meta value
     *
     * @return Entity
     */
    public function addMetadata($key, $val)
    {
        $this->validateMetaItem($key, $val);

        $this->metadata[$key] = $val;

        return $this;
    }

    /**
     * Remove an item of meta
     *
     * @param string $key Meta key
     *
     * @return Entity
     */
    public function removeMetadata($key)
    {
        if (isset($this->metadata[$key])) {
            unset($this->metadata[$key]);
        }

        return $this;
    }

    /**
     * Checks the item being added is valid
     *
     * @throws \OutOfBoundsException If too many metadata items exist
     * @throws \InvalidArgumentException If the key or value are too long
     *
     * @param string $key Meta key
     * @param string $val Meta value
     */
    protected function validateMetaItem($key, $val)
    {
        if (count($this->metadata) === 3) {
            throw new \OutOfBoundsException('Only up to 3 metadata keys are permitted');
        }

        if (mb_strlen($key) > 50) {
            throw new \InvalidArgumentException('Metadata key must be 50 characters or less');
        }

        if (mb_strlen($val) > 200) {
            throw new \InvalidArgumentException('Metadata value must be 200 characters or less');
        }
    }
}
