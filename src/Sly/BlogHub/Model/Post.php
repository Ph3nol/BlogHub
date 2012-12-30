<?php

namespace Sly\BlogHub\Model;

use Sly\BlogHub\Model\Category,
    Sly\BlogHub\Model\Tag,
    Sly\BlogHub\Collection\TagCollection,
    Sly\BlogHub\Parser\PostParser;

use GitElephant\Objects\TreeObject;

/**
 * Post.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Post
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var \Sly\BlogHub\Model\Category
     */
    private $category;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $excerpt;

    /**
     * @var \Sly\BlogHub\Parser\PostParser
     */
    private $parsedContent;

    /**
     * @var \Sly\BlogHub\Collection\TagCollection
     */
    private $tags;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $slug;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->tags      = new TagCollection();
    }

    /**
     * __toString.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get Title value.
     *
     * @return string Title value to get
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set Title value.
     *
     * @param string $title Title value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get Category value.
     *
     * @return \Sly\BlogHub\Model\Category Category value to get
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set Category value.
     *
     * @param \Sly\BlogHub\Model\Category $category Category value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get ParsedContent value.
     *
     * @return \Sly\BlogHub\Parser\PostParser ParsedContent value to get
     */
    public function getParsedContent()
    {
        return $this->parsedContent;
    }
    
    /**
     * Set ParsedContent value.
     *
     * @param \Sly\BlogHub\Parser\PostParser $parsedContent ParsedContent value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setParsedContent($parsedContent)
    {
        $this->parsedContent = $parsedContent;

        return $this;
    }

    /**
     * Get Content value.
     *
     * @return string Content value to get
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Set Content value.
     *
     * @param string $content Content value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get Excerpt value.
     *
     * @return string Excerpt value to get
     */
    public function getExcerpt($suffix = ' [...]')
    {
        if ($this->getParsedContent()->getDescription()) {
            return (string) $this->getParsedContent()->getDescription();
        } else {
            return sprintf('%s%s', $this->excerpt, $suffix);
        }
    }
    
    /**
     * Set Excerpt value.
     *
     * @param string $excerpt Excerpt value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get Tags value.
     *
     * @return \Sly\BlogHub\Collection\TagCollection Tags value to get
     */
    public function getTags()
    {
        return $this->tags;
    }
    
    /**
     * Set Tags value.
     *
     * @param \Sly\BlogHub\Collection\TagCollection $tags Tags value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setTags(TagCollection $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Add a tag.
     *
     * @param \Sly\BlogHub\Model\Tag $tag Tag value to set
     *
     * @return \Sly\BlogHub\Collection\TagCollection
     */
    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);

        return $this->tags;
    }

    /**
     * Get CreatedAt value.
     *
     * @return \DateTime CreatedAt value to get
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set CreatedAt value.
     *
     * @param \DateTime $createdAt CreatedAt value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get UpdatedAt value.
     *
     * @return \DateTime UpdatedAt value to get
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * Set UpdatedAt value.
     *
     * @param \DateTime $updatedAt UpdatedAt value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get Slug value.
     *
     * @return string Slug value to get
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Set Slug value.
     *
     * @param string $slug Slug value to set
     *
     * @return \Sly\BlogHub\Model\Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set data from entry and repository.
     *
     * @param \GitElephant\Objects\TreeObject $entry  Entry
     * @param string                          $gitDir Git/Sources directory
     */
    public function setDataFromEntry(TreeObject $entry, $gitDir)
    {
        $entryTitle    = str_replace(array('.md', '.markdown'), array(), (string) $entry);
        $entrySlug     = \Sly\BlogHub\Util\String::slugify($entryTitle);
        $postPath      = sprintf('%s/%s/%s', $gitDir, $this->getCategory(), $entry->getName());
        $parsedContent = new PostParser(file_get_contents($postPath));

        $this->setTitle($entryTitle);
        $this->setSlug($entrySlug);
        $this->setParsedContent($parsedContent);
        $this->setContent((string) $this->getParsedContent()->getContent());

        foreach ($this->getParsedContent()->getTags() as $tag) {
            $t = new Tag();
            $t->setName($tag);

            $this->addTag($t);
        }

        $this->setExcerpt(substr($this->getContent(), 0, 100));

        if ($this->getParsedContent()->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime($this->getParsedContent()->getCreatedAt()));
        }

        if ($this->getParsedContent()->getUpdatedAt()) {
            $this->setCreatedAt(new \DateTime($this->getParsedContent()->getUpdatedAt()));
        }
    }
}
