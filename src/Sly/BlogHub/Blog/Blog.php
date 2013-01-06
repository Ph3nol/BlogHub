<?php

namespace Sly\BlogHub\Blog;

use GitElephant\Repository,
    GitElephant\Objects\TreeObject;

use Sly\BlogHub\Collection\Collection,
    Sly\BlogHub\Collection\CategoryCollection,
    Sly\BlogHub\Collection\PostCollection,
    Sly\BlogHub\Collection\TagCollection,
    Sly\BlogHub\Model\Category,
    Sly\BlogHub\Model\Post,
    Sly\BlogHub\Query\QueryBuilder;

/**
 * Blog.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Blog
{
    /**
     * @var \GitElephant\Repository
     */
    private $repository;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    private $gitDir;

    /**
     * @var \Sly\BlogHub\Collection\Collection
     */
    private $collections;

    /**
     * @var \Sly\BlogHub\Query\QueryBuilder
     */
    private $query;

    /**
     * Constructor.
     *
     * @param \GitElephant\Repository $repository Repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository  = $repository;
        $this->rootDir     = realpath(dirname(__FILE__).'/../../../');
        $this->gitDir      = sprintf('%s/%s', $this->rootDir, $this->repository->getPath());
        $this->collections = new Collection();

        foreach ($this->getModelsNames() as $modelName) {
            $collectionClassName = sprintf('\Sly\BlogHub\Collection\%sCollection', $modelName);

            $this->collections->add($modelName, new $collectionClassName());
        }

        $this->initEntities();

        $this->query = new QueryBuilder($this);
    }

    /**
     * Initialize categories, posts and tags.
     */
    private function initEntities()
    {
        $branchName = $this->repository->getMainBranch()->getName();

        $categoriesTree = $this->repository->getTree($branchName);

        foreach ($categoriesTree as $treeEntry) {
            if (TreeObject::TYPE_TREE == $treeEntry->getType()) {
                $category      = new Category();
                $categoryPosts = new PostCollection();

                $category->setName((string) $treeEntry);

                $postsTree = $this->repository->getTree($branchName, $treeEntry);

                foreach ($postsTree as $treeEntry) {
                    $post = new Post();
                    $post->setCategory($category);
                    $post->setDataFromEntry($treeEntry, $this->getGitDir());

                    $categoryPosts->add($post);
                    $this->getPosts()->add($post);

                    foreach ($post->getTags() as $tag) {
                        $this->getTags()->add((string) $tag, $tag);
                    }
                }

                $category->setPosts($categoryPosts);

                $this->getCategories()->add($category);
            }
        }
    }

    /**
     * Get models names.
     *
     * @param boolean $withCollection Only with an associated collection
     * 
     * @return array
     */
    private function getModelsNames($withCollection = true)
    {
        $models = array();

        foreach (glob($this->getRootDir().'/*/*/Model/*') as $modelClass) {
            $modelClassInfo = pathinfo($modelClass);

            $collectionClassPath = sprintf(
                '%s/%s.%s',
                str_replace('Model', 'Collection', $modelClassInfo['dirname']),
                $modelClassInfo['filename'].'Collection',
                $modelClassInfo['extension']
            );

            if (false === $withCollection || file_exists($collectionClassPath)) {
                $models[] = $modelClassInfo['filename'];
            }
        }

        return $models;
    }

    /**
     * Get collections.
     * 
     * @return \Sly\BlogHub\Collection\Collection
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * Get Posts value.
     *
     * @return \Sly\BlogHub\Collection\PostCollection Posts value to get
     */
    public function getPosts()
    {
        return $this->getCollections()->get('Post');
    }

    /**
     * Get Categories value.
     *
     * @return \Sly\BlogHub\Collection\CategoryCollection Categories value to get
     */
    public function getCategories()
    {
        return $this->getCollections()->get('Category');
    }

    /**
     * Get Tags value.
     *
     * @return \Sly\BlogHub\Collection\TagCollection Tags value to get
     */
    public function getTags()
    {
        return $this->getCollections()->get('Tag');
    }

    /**
     * Get RootDir value.
     *
     * @return string RootDir value to get
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Get GitDir value.
     *
     * @return string GitDir value to get
     */
    public function getGitDir()
    {
        return $this->gitDir;
    }

    /**
     * Get QueryBuilder.
     * 
     * @return \Sly\BlogHub\Query\QueryBuilder
     */
    public function getQuery()
    {
        return $this->query;
    }
}
