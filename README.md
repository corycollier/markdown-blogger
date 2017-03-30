# Markdown Blogger
[![Build Status](https://travis-ci.org/corycollier/markdown-blogger.svg?branch=master)](https://travis-ci.org/corycollier/markdown-blogger) [![Latest Stable Version](https://poser.pugx.org/corycollier/markdown-blogger/v/stable)](https://packagist.org/packages/corycollier/markdown-blogger) [![Total Downloads](https://poser.pugx.org/corycollier/markdown-blogger/downloads)](https://packagist.org/packages/corycollier/markdown-blogger) [![License](https://poser.pugx.org/corycollier/markdown-blogger/license)](https://packagist.org/packages/corycollier/markdown-blogger)

This project aims to allow users to create blogs by merely uploading markdown files

## Installation
Installation should be running `composer install`

## Usage
Write markdown posts (with the .md suffix) in the `data` folder. Use underscores
to represent slashes in the path_info

example index.php file
```php
require __DIR__ . '/../vendor/autoload.php';

use MarkdownBlogger\Application;

// bootstrap the blog
$app = Application::getInstance();
$app->bootstrap([
    'data_dir'    => realpath(__DIR__ . '/../data'),
    'blog_title'  => 'The Default Title',
    'keywords'    => 'Testing, Stuff',
    'description' => 'The default description',
]);

// run it
$app->run();
```

```
this_is_a-path.md -> this/is/a-path
```
