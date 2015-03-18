<?php namespace GoCardless\Pro\Models\Abstracts;

abstract class Entity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $created_at;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}