<?php

namespace App\Model;

class TestList implements \Countable, \Iterator
{
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;

    private $maxResults = 0;
    private $offset = 0;
    private $limit = 1;
    private $tests = [];
    private $pageIndex = 0;
    private $pageCount = 0;

    private $iteratorPosition = 0;

    public function __construct(array $tests, int $maxResults, int $offset, int $limit)
    {
        $this->maxResults = $maxResults;
        $this->offset = $offset;
        $this->limit = $limit;

        foreach ($tests as $test) {
            if (is_object($test) && $test instanceof Test) {
                $this->tests[] = $test;
            }
        }

        $this->pageIndex = 0 === $this->limit ? 0 : $this->offset / $this->limit;
        $this->pageCount = 0 === $this->limit ? 0 : (int) ceil($this->maxResults / $this->limit);
    }

    public function count(): int
    {
        return count($this->tests);
    }

    public function current(): Test
    {
        return $this->tests[$this->iteratorPosition];
    }

    public function next()
    {
        ++$this->iteratorPosition;
    }

    public function key(): int
    {
        return $this->iteratorPosition;
    }

    public function valid(): bool
    {
        return isset($this->tests[$this->iteratorPosition]);
    }

    public function rewind()
    {
        $this->iteratorPosition = 0;
    }

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    public function getPageNumber(): int
    {
        return $this->pageIndex + 1;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }
}
