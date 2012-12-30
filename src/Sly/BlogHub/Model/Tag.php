<?php

namespace Sly\BlogHub\Model;

/**
 * Tag.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Tag
{
    /**
     * @var string
     */
    private $name;

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
     * @return \Sly\BlogHub\Model\Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
