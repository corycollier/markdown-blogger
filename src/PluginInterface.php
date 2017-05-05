<?php

namespace MarkdownBlogger;

use MarkdownBlogger\Application;

interface PluginInterface
{
    public function initialize(Application $app);
}
