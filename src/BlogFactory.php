<?php

namespace MarkdownBlogger;

class BlogFactory
{
    const ERR_NO_SUCH_BLOG = 'The requested blog [%s] does not exist';

    protected $parser;

    public function __construct()
    {
        $this->parser = new \Parsedown;
    }

    public function factory(Request $request)
    {
        $parser   = $this->getParser();
        $name     = $this->getBlogName($request);
        $filename = $this->getFilename($name);
        $content  = $this->getFileContent($filename);
        $data     = new \SplFileInfo($filename);

        return new Blog([
            'content' => $parser->text($content),
            'data'    => $data,
        ]);
    }

    protected function getParser()
    {
        return $this->parser;
    }

    protected function getFilename($name)
    {
        return __DIR__ . '/../data/' . $name . '.md';
    }

    protected function getFileContent($filename)
    {
        if (!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf(self::ERR_NO_SUCH_BLOG, $filename));
        }
        return file_get_contents($filename);
    }

    public function getBlogName(Request $request)
    {
        $options = [
            'q', 'query_string', 'path_info', 'php_self',
        ];

        foreach ($options as $option) {
            try  {
                $name = $request->get($option);
                return $this->translateName($name);
            } catch (\OutOfRangeException $exception) {
                continue;
            }
        }
    }

    public function translateName($name)
    {
        $name = ltrim($name, '/');
        $name = rtrim($name, '/');

        return preg_replace('/[^A-Za-z0-9\-_]/', '', strtr($name, [
            '/' => '_',
        ]));
    }
}
