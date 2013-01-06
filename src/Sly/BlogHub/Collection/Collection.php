<?php

namespace Sly\BlogHub\Collection;

use \ArrayIterator;

/**
 * Collection.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Collection implements \IteratorAggregate
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
     * Add.
     * 
     * @param string $key        Key
     * @param object $collection Collection
     */
    public function add($key, $collection)
    {
        $this->coll[$key] = $collection;
    }

    /**
     * Get.
     * 
     * @param string $key Key
     * 
     * @return object|false
     */
    public function get($key)
    {
        return isset($this->coll[$key]) ? $this->coll[$key] : false;
    }

    /**
     * Count.
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->getIterator());
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
     * Clear collections.
     */
    public function clear()
    {
        $this->coll = new \ArrayIterator();
    }
}
