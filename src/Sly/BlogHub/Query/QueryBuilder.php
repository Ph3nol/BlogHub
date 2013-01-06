<?php

namespace Sly\BlogHub\Query;

use Sly\BlogHub\Blog\Blog,
    Sly\BlogHub\Exception\QueryException;

/**
 * QueryBuilder.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class QueryBuilder
{
    /**
     * @var \Sly\BlogHub\Blog\Blog
     */
    private $blog;

    /**
     * @var string
     */
    private $model;

    /**
     * @var object
     */
    private $modelCollection;

    /**
     * Constructor.
     * 
     * @param \Sly\BlogHub\Blog\Blog $blog Blog service
     */
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * From.
     * 
     * @param string $modelName Model name
     * 
     * @return \Sly\BlogHub\Collection\Collection;
     */
    public function from($modelName)
    {
        $this->model           = $modelName;
        $this->modelCollection = $this->blog->getCollections()->get($modelName);

        if (false === $this->modelCollection) {
            throw new QueryException(sprintf('"%s" model does not exist', $modelName));
        }

        return $this;
    }

    /**
     * __call.
     *
     * @param string $method    Called method
     * @param string $arguments Called method arguments
     */
    public function __call($method, $arguments)
    {
        if ('getOneBy' == substr($method, 0, 8))
        {
            return $this->getOneBy(substr($method, 8), $arguments[0]);
        }
    }

    /**
     * getOneBy.
     *
     * @todo Process with many elements.
     * 
     * @param string $key   Key
     * @param string $value Value
     * 
     * @return object|false
     */
    public function getOneBy($key, $value)
    {
        $elements = explode('And', $key);

        array_walk_recursive($elements, function(&$key) { $key = lcfirst($key); });

        $modelClass = new \ReflectionClass(sprintf('\Sly\BlogHub\Model\%s', $this->model));

        foreach ($elements as $element) {
            if (
                false === $modelClass->hasProperty($element)
                || false === $modelClass->hasMethod(sprintf('get%s', $element))
            ) {
                throw new QueryException(
                    sprintf(
                        '"%s" property or get-accessor does not exist for %s model',
                        $element,
                        $this->model
                    )
                );
            }
        }

        foreach ($this->modelCollection as $entity) {
            $methodName = sprintf('get%s', ucfirst($elements[0]));

            if ($value == $entity->$methodName()) {
                return $entity;
            }
        }

        return false;
    }
}
