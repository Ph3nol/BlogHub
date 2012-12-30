<?php

namespace Sly\BlogHub\Model;

use Sly\BlogHub\Collection\PostCollection;

/**
 * Category.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Category
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \Sly\BlogHub\Collection\PostCollection
     */
    private $posts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->posts = new PostCollection();
    }

    /**
     * __toString.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get Name value.
     *
     * @return string Name value to get
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set Name value.
     *
     * @param string $name Name value to set
     *
     * @return \Sly\BlogHub\Model\Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Posts value.
     *
     * @return \Sly\BlogHub\Collection\PostCollection Posts value to get
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set Posts value.
     *
     * @param \Sly\BlogHub\Collection\PostCollection $posts Posts value to set
     */
    public function setPosts(PostCollection $posts)
    {
        $this->posts = $posts;
    }
}
