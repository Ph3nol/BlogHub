<?php

namespace Sly\BlogHub\Blog;

use GitElephant\Repository,
    GitElephant\Objects\TreeObject;

use Sly\BlogHub\Collection\CategoryCollection,
    Sly\BlogHub\Collection\PostCollection,
    Sly\BlogHub\Model\Category,
    Sly\BlogHub\Model\Post;

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
     * @var \Sly\BlogHub\Collection\PostCollection
     */
    private $posts;

    /**
     * @var \Sly\BlogHub\Collection\TagCollection
     */
    private $tags;

    /**
     * @var \Sly\BlogHub\Collection\CategoryCollection
     */
    private $categories;

    /**
     * Constructor.
     *
     * @param \GitElephant\Repository $repository Repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->rootDir    = realpath(dirname(__FILE__).'/../../../../');
        $this->gitDir     = sprintf('%s/%s', $this->rootDir, $this->repository->getPath());
        $this->categories = new CategoryCollection();
        $this->posts      = new PostCollection();

        $this->initCategoriesAndPosts();
    }

    /**
     * Initialize categories and posts.
     */
    private function initCategoriesAndPosts()
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
                    $this->posts->add($post);
                }

                $category->setPosts($categoryPosts);

                $this->categories->add($category);
            }
        }
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
     * Get Categories value.
     *
     * @return \Sly\BlogHub\Collection\CategoryCollection Categories value to get
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
