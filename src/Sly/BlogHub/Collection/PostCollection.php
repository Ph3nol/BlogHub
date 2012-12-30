<?php

namespace Sly\BlogHub\Collection;

use Sly\BlogHub\Model\Post;

/**
 * PostCollection.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class PostCollection implements \IteratorAggregate
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
     * @param \Sly\BlogHub\Model\Post $post Post
     */
    public function add(Post $post)
    {
        $this->coll[] = $post;
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
     * Clear posts.
     */
    public function clear()
    {
        $this->coll = new \ArrayIterator();
    }
}
