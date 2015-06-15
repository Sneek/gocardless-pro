<?php

namespace GoCardless\Pro\Models\Abstracts;

abstract class Entity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $created_at;

    /** @var array */
    protected $links = [];

    /**
     * @param $id
     *
     * @return static
     */
    public static function withId($id)
    {
        return (new static)->setId($id);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
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

    /**
     * Return all links associated with the Entity
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Return a single link from the entity
     *
     * @param string $link Key of link to return
     *
     * @return string Link ID or null if not set
     */
    public function getLink($link)
    {
        if ( ! isset($this->links[$link])) {
            return null;
        }

        return $this->links[$link];
    }
}
