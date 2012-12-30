<?php

namespace Sly\BlogHub\Collection;

use Sly\BlogHub\Model\Category;

/**
 * CategoryCollection.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class CategoryCollection implements \IteratorAggregate
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
     * @param \Sly\BlogHub\Model\Category $category Category
     */
    public function add(Category $category)
    {
        $this->coll[] = $category;
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
     * Clear categories.
     */
    public function clear()
    {
        $this->coll = new \ArrayIterator();
    }
}
