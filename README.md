# BlogHub

Use a Git repository to generate blog entities and contents.

=====

## WORK IN PROGRESS.

=====

## Requirements

* PHP 5.3+

## Installation

### Add to your project Composer packages

Just add `sly/blog-hub` package to the requirements of your Composer JSON configuration file,
and run `php composer.phar install` to install it.

### Install from GitHub

Clone this library from Git with `git clone https://github.com/Ph3nol/BlogHub.git`.

Goto to the library directory, get Composer phar package and install vendors:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
```

You're ready to go.

## Repository structure

Your git repository has to respect a Categories/Posts structure to be
understood by the library.

The logic:

```
Category 1
   `-- Title of my post 1
   `-- Title of my post 2
   `-- Title of my post 3

Category 2
   `-- Title of my post 4
```

To match this logic, the repository tree will be:

```
Category 1/
Category 1/Title of my post 1.md
Category 1/Title of my post 2.md
Category 1/Title of my post 3.md
Category 2/
Category 2/Title of my post 4.md
```

## Post structure

Each post has to be a markdown file, into a category folder.
Informations can be added to it as PHPDoc/Annotations one.
Here is an example:

```
/**
 * @createdAt 2013-01-01
 * @description This is my post description.
 * @tags tag1, tag2, tag3
 * @format markdown
 */

# My post title

My post content.
```

## Example

``` php
require_once 'vendor/autoload.php';

use GitElephant\Repository;
use Sly\BlogHub\Blog\Blog;

/**
 * GitElephant repository instance.
 */
$repo = new Repository('/path/to/your/repository');
$repo->checkout('master');

/**
 * BlogHub instance, with repository object as argument.
 */
$blog = new Blog($repo);
```

### Categories list

``` php
foreach ($blog->getCategories() as $category) {
    // Your logic
}
```

**Elements:**

* Category name: `(string) $category` or `$category->getName()`
* Posts collection: `$category->getPosts()`
* Number of posts: `$category->getPosts()->count()`

### Posts list

``` php
foreach ($blog->getPosts() as $post) {
    // Your logic
}
```

``` php
foreach ($category->getPosts() as $post) {
    // Your logic
}
```

**Elements:**

* Post title: `(string) $post` or `$post->getTitle()`
* Post category object: `$post->getCategory()`
* Slug: `$post->getSlug()`
* Excerpt: `$post->getExcerpt([string $separator = ' [...]'])`
* Tags collection: `$post->getTags()`
* Creation DateTime: `$post->getCreatedAt()`
* Update DateTime: `$post->getUpdatedAt()`

### Tags list

``` php
foreach ($blog->getTags() as $tag) {
    // Your logic
}
```

``` php
foreach ($post->getTags() as $tag) {
    // Your logic
}
```

**Elements:**

* Tag name: `(string) $tag` or `$tag->getName()`


## Query Builder

You can use the QueryBuilder to retreive a specific model entity.
Here is an example:

``` php
$query = $blog->getQuery(); // Get QueryBuilder from Blog Manager service
$post  = $query->from('Post')->getOneBySlug('hello-world'); // Get the Post from its slug
```
