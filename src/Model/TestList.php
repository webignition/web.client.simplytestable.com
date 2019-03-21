<?php

namespace App\Model;

class TestList
{
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;

    private $maxResults = 0;
    private $offset = 0;
    private $limit = 1;
    private $tests = [];

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
    }

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
