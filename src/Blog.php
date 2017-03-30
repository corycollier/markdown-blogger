<?php

namespace MarkdownBlogger;

use Symfony\Component\DomCrawler\Crawler;

class Blog
{
    protected $data;
    protected $crawler;

    public function __construct($data)
    {
        $defaults = [
            'content' => '',
            'data'    => null,
        ];

        $data = array_merge($defaults, $data);

        $this->data = $data;
        $this->crawler = new Crawler($data['content']);
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
        return $data['data']->getCTime();
    }

    /**
     * Gets the title of the blog post
     * @return string
     */
    public function getTitle()
    {
        $crawler = $this->getCrawler();
        return $crawler->filter('h1')->html();
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
        return array_slice(array_keys($results), 0, 20);
    }
}
