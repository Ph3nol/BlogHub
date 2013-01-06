<?php

require_once __DIR__.'/../vendor/autoload.php';

use GitElephant\Repository;
use Sly\BlogHub\Blog\Blog;

$repo = new Repository('../example/content');
$repo->checkout('master');

$blog = new Blog($repo);

?>

<h2>Statistics</h2>

<ul>
    <li>Total categories: <strong><?php echo $blog->getCategories()->count() ?></strong></li>
    <li>Total posts: <strong><?php echo $blog->getPosts()->count() ?></strong></li>
    <li>Total tags: <strong><?php echo $blog->getTags()->count() ?></strong></li>
</ul>

<h2>Categories and Posts</h2>

<ul>
    <?php foreach ($blog->getCategories() as $c): ?>
        <li>
            <strong><?php echo $c->getName() ?></strong> (<?php echo $c->getPosts()->count() ?> posts)

            <ul>
                <?php foreach ($c->getPosts() as $p): ?>
                    <li>
                        <strong><?php echo $p->getCreatedAt()->format('Y-m-d') ?></strong>
                        <?php echo $p->getTitle() ?>
                        <br />
                        <small><?php echo $p->getExcerpt() ?></small>

                        <?php if ($p->getTags()): ?>
                            <ul>
                                <?php foreach ($p->getTags() as $tag): ?>
                                    <li><?php echo $tag ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
    <?php endforeach ?> 
</ul>

<h2>Specific Post</h2>

<?php
    $query = $blog->getQuery();
    $post  = $query->from('Post')->getOneBySlug('this-is-a-test');
?>

<h3><?php echo $post->getTitle() ?></h3>

<p><?php echo $post->getExcerpt() ?></p>
