<?php

namespace App\Model;

use App\Entity\Test\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;

class RemoteTestList
{
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;

    private $maxResults = 0;
    private $offset = 0;
    private $limit = 1;

    /**
     * @var array
     */
    private $tests = array();

    public function __construct(array $remoteTests, int $maxResults, int $offset, int $limit)
    {
        $this->maxResults = $maxResults;
        $this->offset = $offset;
        $this->limit = $limit;

        foreach ($remoteTests as $remoteTest) {
            if (is_object($remoteTest) && $remoteTest instanceof RemoteTest) {
                $this->addRemoteTest($remoteTest);
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

    public function addRemoteTest(RemoteTest $remoteTest)
    {
        if (!isset($this->tests[$remoteTest->getId()])) {
            $this->tests[$remoteTest->getId()] = array();
        }

        $this->tests[$remoteTest->getId()]['remote_test'] = $remoteTest;
    }

    public function addTest(Test $test)
    {
        if (!isset($this->tests[$test->getTestId()])) {
            $this->tests[$test->getTestId()] = array();
        }

        $this->tests[$test->getTestId()]['test'] = $test;
    }

    public function get(): array
    {
        return $this->tests;
    }

    public function getLength(): int
    {
        return count($this->tests);
    }

    public function isEmpty(): bool
    {
        return $this->getLength() === 0;
    }

    public function getPageNumber(): int
    {
        return $this->getPageIndex() + 1;
    }

    public function getPageCount(): int
    {
        return (int)ceil($this->getMaxResults() / $this->getLimit());
    }

    public function getPageIndex(): int
    {
        return $this->getOffset() / $this->getLimit();
    }

    public function requiresResults(Test $test): bool
    {
        if (!$this->containsLocal($test->getTestId()) || !$this->containsRemote($test->getTestId())) {
            return false;
        }

        /* @var RemoteTest $remoteTest */
        $remoteTest = $this->tests[$test->getTestId()]['remote_test'];

        $decoratedTest = new DecoratedTest($test, $remoteTest);

        return $decoratedTest->requiresRemoteTasks();
    }

    private function containsRemote(int $testId): bool
    {
        if (!isset($this->tests[$testId])) {
            return false;
        }

        return isset($this->tests[$testId]['remote_test']);
    }

    private function containsLocal(int $testId): bool
    {
        if (!isset($this->tests[$testId])) {
            return false;
        }

        return isset($this->tests[$testId]['test']);
    }

    public function getPageCollectionNumber(): int
    {
        return $this->getPageIndex() + 1;
    }

    public function getPageCollectionIndex(): int
    {
        return (int)floor($this->getPageIndex() / self::PAGINATION_PAGE_COLLECTION_SIZE);
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
        $hashableContent = json_encode($this->getPropertiesString());

        foreach ($this->get() as $testId => $testData) {
            $testDataHashableContent = array();

            if ($this->containsLocal($testId)) {
                $testDataHashableContent['requires_results'] = $this->requiresResults($testData['test']);
            }

            if ($this->containsRemote($testId) && $this->requiresResults($testData['test'])) {
                /* @var RemoteTest $remoteTest */
                $remoteTest = $testData['remote_test'];

                $testDataHashableContent['remote_test'] = $remoteTest->getSource();
            }

            $hashableContent .= json_encode($testDataHashableContent);
        }

        return $hashableContent;
    }

    private function getPropertiesString(): string
    {
        return json_encode(array(
            'max_results' => $this->getMaxResults(),
            'offset' => $this->getOffset(),
            'limit' => $this->getLimit(),
            'page_index' => $this->getPageIndex(),
            'page_collection_index' => $this->getPageCollectionIndex(),
            'length' => $this->getLength()
        ));
    }
}
