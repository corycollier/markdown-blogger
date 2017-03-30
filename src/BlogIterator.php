<?php

namespace MarkdownBlogger;

class BlogIterator extends \DirectoryIterator
{
    /**
     * Function to determine if this file is a valid blog post
     * @return boolean true if not a 404 page, an empty file, or a folder
     */
    public function isBlogPost()
    {
        $skips = ['404.md', 'front-page.md'];
        if ($this->getExtension() != 'md') {
            return false;
        }
        if (in_array($this->getFilename(), $skips)) {
            return false;
        }
        return true;
    }

    /**
     * Returns the most recent blog posts
     * @param  integer $limit The maximum number of items to return
     * @return array An array of arrays representing blog posts
     */
    public function getLatest($limit = 10)
    {
        $results = [];
        while ($this->valid()) {
            if ($this->isBlogPost()) {
                $item = $this->current();
                $results[$item->getCTime()] = [
                    'q'        => $item->getFilename(),
                    'data_dir' => dirname($item->getPathname()),
                ];
            }
            $this->next();
        }
        krsort($results);
        return array_slice($results, 0, $limit);
    }
}
