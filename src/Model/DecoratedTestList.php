<?php

namespace App\Model;

use App\Model\Test\DecoratedTest;

class DecoratedTestList implements \Iterator
{
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;

    private $maxResults = 0;
    private $offset = 0;
    private $limit = 1;

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
        return count($this->tests);
    }

    public function getPageNumber(): int
    {
        return $this->getPageIndex() + 1;
    }

    public function getPageCount(): int
    {
        if (0 === $this->getLimit()) {
            return 0;
        }

        return (int) ceil($this->getMaxResults() / $this->getLimit());
    }

    public function getPageIndex(): int
    {
        if (0 === $this->getLimit()) {
            return 0;
        }

        return $this->getOffset() / $this->getLimit();
    }

    public function getPageCollectionIndex(): int
    {
        return (int) floor($this->getPageIndex() / self::PAGINATION_PAGE_COLLECTION_SIZE);
    }

    /**
     * @return int[]
     */
    public function getPageNumbers(): array
    {
        $pageNumbers = [];

        if ($this->getMaxResults() <= $this->getLimit()) {
            return $pageNumbers;
        }

        $start = $this->getPageCollectionIndex() * $this->getLimit();
        $end = $start + $this->getLimit() - 1;

        for ($pageIndex = $start; $pageIndex <= $end; $pageIndex++) {
            if ($this->isValidPageIndex($pageIndex)) {
                $pageNumbers[] = $pageIndex + 1;
            }
        }

        return $pageNumbers;
    }

    private function isValidPageIndex(int $index): bool
    {
        return $this->getMaxResults() > ($index) * $this->getLimit();
    }

    public function getHash(): string
    {
        return md5($this->getHashableContent());
    }

    private function getHashableContent(): string
    {
        $hashableData = [
            'max_results' => $this->getMaxResults(),
            'offset' => $this->getOffset(),
            'limit' => $this->getLimit(),
            'test_data' => [],
        ];

        foreach ($this->tests as $decoratedTest) {
            $testHashableData = [];

            $requiresRemoteTasks = $decoratedTest->requiresRemoteTasks();

            $testHashableData['requires_remote_tasks'] = $requiresRemoteTasks;
            if ($requiresRemoteTasks) {
                $testHashableData['data'] = $decoratedTest->__toArray();
            }

            $hashableData['test_data'][$decoratedTest->getTestId()] = $testHashableData;
        }

        return json_encode($hashableData);
    }
}
