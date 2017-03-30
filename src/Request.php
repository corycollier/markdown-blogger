<?php

namespace MarkdownBlogger;

class Request
{
    const ERR_BAD_REQUEST_KEY = 'Requested data [%s] is not present in the request';
    protected $data;

    public function __construct($data = [])
    {
        $this->data = array_change_key_case($data);
    }

    public function get($key)
    {
        $data = $this->getData();
        if (! array_key_exists($key, $data)) {
            throw new \OutOfRangeException(sprintf(self::ERR_BAD_REQUEST_KEY, $key));
        }
        return $data[$key];
    }

    protected function getData()
    {
        return $this->data;
    }
}
