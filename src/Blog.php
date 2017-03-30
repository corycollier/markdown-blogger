<?php

namespace MarkdownBlogger;

use Symfony\Component\DomCrawler\Crawler;

class Blog
{
    protected $data;
    protected $crawler;

    /**
     * constructor
     * @param Array $data The data to use for populating the blog instance.
     */
    public function __construct($data = [])
    {
        $this->setData($data);
        $this->crawler = new Crawler($data['content']);
    }

    /**
     * Setter for the data property
     * @param Array $data the array of data for the blog Instance.
     */
    protected function setData($data = [])
    {
        $defaults = [
            'content'  => '',
            'data'     => null,
        ];

        $this->data = array_merge($defaults, $data);

        return $this;
    }

    /**
     * Getter for the data property
     * @return array
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Getter for the Crawler instance
     * @return Symfony\Component\DomCrawler\Crawler;
     */
    protected function getCrawler()
    {
        return $this->crawler;
    }

    /**
     * Gets the Creation Time of the Blog Post
     * @return string
     */
    public function getCreationTime()
    {
        $data = $this->getData();

        if ($data['data'] instanceof \SplFileInfo) {
            return $data['data']->getCTime();
        }
    }

    /**
     * Gets the title of the blog post
     * @return string
     */
    public function getTitle()
    {
        $crawler = $this->getCrawler();

        $result = $crawler->filter('h1');
        if ($result->count()) {
            return $result->html();
        }
    }

    /**
     * Gets the description of the blog post
     * @return string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Gets the blog post content
     * @return string
     */
    public function getContent()
    {
        $data = $this->getData();
        return $data['content'];
    }

    public function getLink()
    {
        $data = $this->getData();
        $filename = $data['data']->getFilename();
        return strtr($filename, [
            '.md' => '',
            '_' => '/',
        ]);
    }

    public function getTime()
    {
        return date('F j, Y', $this->getCreationTime());
    }

    public function getSnippet($length = 50)
    {
        $template = PHP_EOL . '<h2><a href="%s">%s</a></h2>'
            . PHP_EOL . '<small>%s</small>'
            . PHP_EOL . '<p>%s</p>'
            . PHP_EOL;

        $title    = $this->getTitle();
        $link     = $this->getLink();
        $time     = $this->getTime();
        $text     = strip_tags($this->getCrawler()->html());
        $words    = str_word_count($text, 1);
        $content  = implode(' ', array_slice($words, 0, $length));

        echo sprintf($template, $link, $title, $time, $content);
    }

    /**
     * Gets the keywords of the blog post, by finding the most common words in the post
     * @return array
     */
    public function getKeywords()
    {
        $results = [];
        $data = $this->getData();
        $text = strip_tags($data['content']);
        $words = str_word_count($text, 1);
        foreach ($words as $word) {
            if (strlen($word) < 4) {
                continue;
            }
            if (! array_key_exists($word, $results)) {
                $results[$word] = 0;
            }
            $results[$word]++;
        }

        arsort($results);
        $results = array_slice(array_keys($results), 0, 20);
        return implode(', ', $results);
    }
}
