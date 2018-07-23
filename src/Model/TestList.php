<?php

namespace App\Model;

use App\Entity\Test\Test;
use App\Model\RemoteTest\RemoteTest;

class TestList
{
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;

    /**
     * @var int
     */
    private $maxResults = 0;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var int
     */
    private $limit = 1;

    /**
     * @var Test[]
     */
    private $tests = array();

    /**
     * @param int $maxResults
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }

    /**
     * @return int
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param RemoteTest $remoteTest
     */
    public function addRemoteTest(RemoteTest $remoteTest)
    {
        if (!isset($this->tests[$remoteTest->getId()])) {
            $this->tests[$remoteTest->getId()] = array();
        }

        $this->tests[$remoteTest->getId()]['remote_test'] = $remoteTest;
    }

    /**
     * @param Test $test
     */
    public function addTest(Test $test)
    {
        if (!isset($this->tests[$test->getTestId()])) {
            $this->tests[$test->getTestId()] = array();
        }

        $this->tests[$test->getTestId()]['test'] = $test;
    }

    /**
     * @return Test[]
     */
    public function get()
    {
        return $this->tests;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return count($this->tests);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->getLength() === 0;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->getPageIndex() + 1;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return (int)ceil($this->getMaxResults() / $this->getLimit());
    }

    /**
     * @return int
     */
    public function getPageIndex() {
        return $this->getOffset() / $this->getLimit();
    }

    /**
     * @param Test $test
     *
     * @return bool|null
     */
    public function requiresResults(Test $test)
    {
        if (!$this->containsLocal($test->getTestId()) || !$this->containsRemote($test->getTestId())) {
            return null;
        }

        /* @var RemoteTest $remoteTest */
        $remoteTest = $this->tests[$test->getTestId()]['remote_test'];

        return $remoteTest->getTaskCount() != $test->getTaskCount();
    }

    /**
     * @param int $testId
     *
     * @return bool
     */
    private function containsRemote($testId)
    {
        if (!isset($this->tests[$testId])) {
            return false;
        }

        return isset($this->tests[$testId]['remote_test']);
    }

    /**
     * @param int $testId
     *
     * @return bool
     */
    private function containsLocal($testId)
    {
        if (!isset($this->tests[$testId])) {
            return false;
        }

        return isset($this->tests[$testId]['test']);
    }

    /**
     * @return int
     */
    public function getPageCollectionNumber()
    {
        return $this->getPageIndex() + 1;
    }

    /**
     * @return int
     */
    public function getPageCollectionIndex()
    {
        return (int)floor($this->getPageIndex() / self::PAGINATION_PAGE_COLLECTION_SIZE);
    }

    /**
     * @return int[]
     */
    public function getPageNumbers()
    {
        if ($this->getMaxResults() <= $this->getLimit()) {
            return array();
        }


        $start = $this->getPageCollectionIndex() * $this->getLimit();
        $end = $start + $this->getLimit() - 1;

        $pageNumbers = array();

        for ($pageIndex = $start; $pageIndex <= $end; $pageIndex++) {
            if ($this->isValidPageIndex($pageIndex)) {
                $pageNumbers[] = $pageIndex + 1;
            }
        }

        return $pageNumbers;
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    private function isValidPageIndex($index)
    {
        return $this->getMaxResults() > ($index) * $this->getLimit();
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return md5($this->getHashableContent());
    }

    /**
     * @return string
     */
    private function getHashableContent()
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

    /**
     * @return string
     */
    private function getPropertiesString()
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
