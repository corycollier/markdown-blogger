<?php

namespace MarkdownBlogger;

class Application
{
    /**
     * Holds the instance of self
     */
    protected static $instance;

    protected $iterator;
    protected $request;
    protected $factory;

    /**
     * Privatizing the constructor, to enforce the singleton pattern
     */
    private function __construct()
    {

    }

    /**
     * Standard method to get the single instance of self
     * @return Application
     */
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Getter for the BlogIterator
     * @return BlogIterator
     */
    protected function getIterator()
    {
        return $this->iterator;
    }

    /**
     * Getter for the Request object
     * @return Request
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * Getter for the Blog Factory
     * @return BlogFactory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * Gets all of the things ready.
     * @param  array $config Configuration parameters.
     * @return Application Return self, for object-chaining
     */
    public function bootstrap($config = [])
    {
        $this->iterator = new BlogIterator(__DIR__ . '/../data');
        $this->request  = new Request(array_merge($_SERVER, $_GET));
        $this->factory  = new BlogFactory;

        return $this;
    }

    /**
     * Run the application.
     * @return Application Return self, for object-chaining.
     */
    public function run()
    {
        $request = $this->getRequest();
        $factory = $this->getFactory();
        try {
            $blog = $factory->factory($request);
        } catch (\InvalidArgumentException $exception) {
            $request = $this->get404Request();
            $blog = $factory->factory($request);
        }

        $vars = [
            'content'     => $blog->getContent(),
            'title'       => $blog->getTitle(),
            'keywords'    => $blog->getKeywords(),
            'description' => $blog->getDescription(),
        ];

        include __DIR__ . '/../web/layout.php';

        return $this;
    }

    /**
     * Utility method to get a reuest object referencing a 404 page.
     * @return Request
     */
    protected function get404Request()
    {
        return new Request([
            'q' => '404'
        ]);
    }
}
