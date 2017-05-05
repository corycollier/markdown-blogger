<?php

namespace MarkdownBlogger;

class Application
{
    const ERR_INVALID_CONFIG_KEY = 'The config key requested [%s] does not exist';

    protected static $instance;
    protected $iterator;
    protected $request;
    protected $factory;
    protected $config;

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
     * Setter for the config property.
     * @param Array $config The configuration Array.
     * @return Application Returns self, for object-chaining.
     */
    protected function setConfig($config = [])
    {
        $defaults = [
            'data_dir' => '',
        ];

        $this->config = array_merge($defaults, $config);
        return $this;
    }

    /**
     * Getter for the config property
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Gets a config value by key
     * @param  string $key The key to get.
     * @return mixed Could be anything.
     */
    public function get($key)
    {
        $config = $this->getConfig();
        if (! array_key_exists($key, $config)) {
            throw new \OutOfRangeException(sprintf(self::ERR_INVALID_CONFIG_KEY, $key));
        }
        return $config[$key];
    }

    /**
     * Gets all of the things ready.
     * @param  array $config Configuration parameters.
     * @return Application Return self, for object-chaining
     */
    public function bootstrap($config = [])
    {
        $config         = $this->setConfig($config)->getConfig();
        $this->request  = $this->getNewRequest(array_merge($_SERVER, $_GET));
        $this->iterator = new BlogIterator(realpath($config['data_dir']));
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
        $iterator = $this->getIterator();
        $latest = $iterator->getLatest();
        try {
            $blog = $factory->factory($request->getData());
        } catch (\InvalidArgumentException $exception) {
            $request = $this->getNewRequest(['q' => '404']);
            $blog = $factory->factory($request->getData());
        }

        $vars = [
            'content'     => $blog->getContent(),
            'title'       => $blog->getTitle(),
            'keywords'    => $blog->getKeywords(),
            'description' => $blog->getDescription(),
            'latest'      => $factory->massFactory($latest),
            'blog_title'  => $this->get('blog_title'),
            'time'        => $blog->getTime(),
        ];

        include __DIR__ . '/../web/layout.php';

        return $this;
    }

    /**
     * Gets a new request object with provided data
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    protected function getNewRequest($data = [])
    {
        $data = array_merge(['data_dir' => $this->get('data_dir')], $data);
        return new Request($data);
    }
}
