<?php

namespace MarkdownBlogger;

class BlogFactory
{
    const ERR_NO_SUCH_BLOG = 'The requested blog [%s] does not exist';

    protected $parser;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parser = new \Parsedown;
    }

    /**
     * Main public entry point. Returns a Blog value for the given array.
     * @param  array $data An array of data.
     * @return Blog The subsequent blog instance.
     */
    public function factory($data)
    {
        $content  = '';
        $file     = '';
        $parser   = $this->getParser();
        $filename = $this->getFilename($data);

        if ($filename) {
            $content  = $this->getFileContent($filename);
            $file     = new \SplFileInfo($filename);
        }

        return new Blog([
            'content' => $parser->text($content),
            'data'    => $file,
        ]);
    }

    /**
     * Factory for multiple items
     * @param  array $data An array of items
     * @return array An array of Blogs
     */
    public function massFactory($data)
    {
        $results = [];
        foreach ($data as $post) {
            $results[] = $this->factory($post);
        }
        return $results;
    }

    /**
     * Getter for the parser property
     * @return Parsedown The parsedown instance.
     */
    protected function getParser()
    {
        return $this->parser;
    }

    /**
     * Utility method to wrap the file_get_contents PHP function.
     * @param  string $filename The path to get content from.
     * @return string The contents of the file.
     */
    protected function getFileContent($filename)
    {
        if (!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf(self::ERR_NO_SUCH_BLOG, $filename));
        }
        return file_get_contents($filename);
    }

    /**
     * Translates a request value, to a blog file name
     * @param  string $name The url value given
     * @return string the file name
     */
    protected function translateName($name)
    {
        if ($name === '/index.php') {
            return '';
        }

        $name = ltrim($name, '/');
        $name = rtrim($name, '/');

        return preg_replace('/[^A-Za-z0-9\-_]/', '', strtr($name, [
            '/' => '_',
            '.md' => '',
        ]));
    }

    /**
     * Gets a filename from the given request url.
     * @param  array $data The array of parameters.
     * @return string The corresponding filepath value.
     */
    protected function getFilename($data)
    {
        $options = [
            'q', 'query_string', 'path_info', 'php_self',
        ];
        $root = $data['data_dir'];

        foreach ($options as $option) {
            if (!array_key_exists($option, $data)) {
                continue;
            }
            $name = $this->translateName($data[$option]);
            if ($name) {
                return $root . '/' . $name . '.md';
            }
        }
    }
}
