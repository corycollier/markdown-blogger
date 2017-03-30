<?php
require __DIR__ . '/../vendor/autoload.php';

use MarkdownBlogger\Application;

// bootstrap the blog
$app = Application::getInstance();
$app->bootstrap([
    /* configuration params here */
]);

// run it
$app->run();
