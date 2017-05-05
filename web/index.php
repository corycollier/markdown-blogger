<?php
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
