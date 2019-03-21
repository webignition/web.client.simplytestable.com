<?php

namespace App\Model;

class DecoratedTestList implements \Iterator
{
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;

    private $maxResults = 0;
    private $offset = 0;
    private $limit = 1;
    private $length = 0;
    private $pageIndex = 0;
    private $pageCount = 0;
    private $pageCollectionIndex = 0;

    private $iteratorPosition = 0;

    /**
     * @var DecoratedTest[]
     */
    private $tests = [];

    public function __construct(array $tests, int $maxResults, int $offset, int $limit)
    {
        $this->maxResults = $maxResults;
        $this->offset = $offset;
        $this->limit = $limit;

        foreach ($tests as $test) {
            if (is_object($test) && $test instanceof DecoratedTest) {
                $this->tests[] = $test;
            }
        }

        $this->length = count($this->tests);
        $this->pageIndex = 0 === $this->limit ? 0 : $this->offset / $this->limit;
        $this->pageCount = 0 === $this->limit ? 0 : (int) ceil($this->maxResults / $this->limit);
        $this->pageCollectionIndex = (int) floor($this->pageIndex / self::PAGINATION_PAGE_COLLECTION_SIZE);
    }

    public function current(): DecoratedTest
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

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getPageNumber(): int
    {
        return $this->pageIndex + 1;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function getPageIndex(): int
    {
        return $this->pageIndex;
    }

    public function getPageCollectionIndex(): int
    {
        return $this->pageCollectionIndex;
    }

    /**
     * @return int[]
     */
    public function getPageNumbers(): array
    {
        $pageNumbers = [];

        if ($this->maxResults <= $this->limit) {
            return $pageNumbers;
        }

        $start = $this->pageCollectionIndex * $this->limit;
        $end = $start + $this->limit - 1;

        for ($pageIndex = $start; $pageIndex <= $end; $pageIndex++) {
            if ($this->isValidPageIndex($pageIndex)) {
                $pageNumbers[] = $pageIndex + 1;
            }
        }

        return $pageNumbers;
    }

    private function isValidPageIndex(int $index): bool
    {
        return $this->maxResults > ($index) * $this->limit;
    }

    public function getHash(): string
    {
        return md5($this->getHashableContent());
    }

    private function getHashableContent(): string
    {
        $hashableData = [
            'max_results' => $this->maxResults,
            'offset' => $this->offset,
            'limit' => $this->limit,
            'test_data' => [],
        ];

        foreach ($this->tests as $decoratedTest) {
            $hashableData['test_data'][$decoratedTest->getTestId()] = $decoratedTest->getHash();
        }

        return json_encode($hashableData);
    }
}
