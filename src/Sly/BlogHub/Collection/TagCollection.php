<?php

namespace Sly\BlogHub\Collection;

use Sly\BlogHub\Model\Tag;

/**
 * TagCollection.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class TagCollection implements \IteratorAggregate
{
    /**
     * @var \ArrayIterator
     */
    private $coll;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->coll = new \ArrayIterator();
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->coll;
    }

    /**
     * @param \Sly\BlogHub\Model\Tag $tag Tag
     */
    public function add(Tag $tag)
    {
        $this->coll[] = $tag;
    }

    /**
     * Count.
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->coll);
    }

    /**
     * isEmpty.
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return (bool) $this->count();
    }

    /**
     * Clear tags.
     */
    public function clear()
    {
        $this->coll = new \ArrayIterator();
    }
}
