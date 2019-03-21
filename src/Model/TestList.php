<?php

namespace App\Model;

class TestList implements \Countable, \Iterator
{
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;

    /**
     * @var TestInterface[]
     */
    private $tests = [];
    private $maxResults = 0;
    private $offset = 0;
    private $limit = 1;
    private $pageIndex = 0;
    private $pageCount = 0;
    private $pageCollectionIndex = 0;

    private $iteratorPosition = 0;

    public function __construct(array $tests, int $maxResults, int $offset, int $limit)
    {
        $this->maxResults = $maxResults;
        $this->offset = $offset;
        $this->limit = $limit;

        foreach ($tests as $test) {
            if (is_object($test) && $test instanceof TestInterface) {
                $this->tests[] = $test;
            }
        }

        $this->pageIndex = 0 === $this->limit ? 0 : $this->offset / $this->limit;
        $this->pageCount = 0 === $this->limit ? 0 : (int) ceil($this->maxResults / $this->limit);
        $this->pageCollectionIndex = (int) floor($this->pageIndex / self::PAGINATION_PAGE_COLLECTION_SIZE);
    }

    public function count(): int
    {
        return count($this->tests);
    }

    public function current(): TestInterface
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
            $isValidPageIndex = $this->maxResults > ($pageIndex) * $this->limit;

            if ($isValidPageIndex) {
                $pageNumbers[] = $pageIndex + 1;
            }
        }

        return $pageNumbers;
    }

    public function getHash(): string
    {
        return md5($this->createHashContent());
    }

    /**
     * @param TestInterface[] $tests
     *
     * @return TestList
     */
    public function withTests(array $tests): TestList
    {
        return new TestList(
            $tests,
            $this->maxResults,
            $this->offset,
            $this->limit
        );
    }

    private function createHashContent(): string
    {
        $hashData = [
            'max_results' => $this->maxResults,
            'offset' => $this->offset,
            'limit' => $this->limit,
            'test_data' => [],
        ];

        foreach ($this->tests as $test) {
            $hashData['test_data'][$test->getTestId()] = $test->getHash();
        }

        return json_encode($hashData);
    }
}
