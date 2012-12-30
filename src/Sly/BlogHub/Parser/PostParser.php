<?php

namespace Sly\BlogHub\Parser;

/**
 * PostParser.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class PostParser
{
    const TAGS_SEPARATOR = ', ';

    /**
     * @var string
     */
    private $rootContent;

    /**
     * @var array
     */
    private $elements;

    /**
     * Constructor.
     * 
     * @param string $rootContent Root content
     */
    public function __construct($rootContent)
    {
        $this->rootContent = $rootContent;

        $this->elements = preg_match_all('/@([a-z][A-Z]*) (.*)\\n/i', $rootContent, $matches);
        $this->elements = array_combine($matches[1], $matches[2]);

        $contentElement = explode('*/', $rootContent);

        $this->elements['content'] = $contentElement[1];
    }

    /**
     * Get tags.
     * 
     * @return array
     */
    public function getTags()
    {
        return isset($this->elements['tags'])
            ? explode(self::TAGS_SEPARATOR, $this->elements['tags'])
            : array()
        ;
    }

    /**
     * __call.
     *
     * @param string $method    Called method
     * @param string $arguments Called method arguments
     */
    public function __call($method, $arguments)
    {
        if ('get' == substr($method, 0, 3))
        {
            $elementKey = lcfirst(substr($method, 3));

            return (array_key_exists($elementKey, $this->elements))
                ? $this->elements[$elementKey]
                : null
            ;
        }
    }
}
