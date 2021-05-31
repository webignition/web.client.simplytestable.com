<?php
namespace App\Model\Test\Task;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Task\Task;

class ErrorTaskMap
{
    /**
     * @var string
     */
    private $message = '';

    /**
     * @var ArrayCollection
     */
    private $tasks;

    /**
     * @param string $message
     */
    public function __construct($message = '')
    {
        $this->setMessage($message);
        $this->tasks = new ArrayCollection();
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function match($message)
    {
        return strtolower($this->getMessage()) == strtolower($message);
    }

    /**
     * @return string
     */
    public function getMessageHash()
    {
        return md5($this->normalisedMessageForHashing($this->getMessage()));
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function normalisedMessageForHashing($message)
    {
        return trim(str_replace('&#39;', "'", $message));
    }

    /**
     * @return ArrayCollection|Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        if (!$this->getTasks()->contains($task)) {
            $this->getTasks()->add($task);
        }
    }

    /**
     * @return int
     */
    public function getCumulativeOccurrenceCount()
    {
        $count = 0;

        foreach ($this->getTasks() as $task) {
            if ($task->getOutput()->hasResult()) {
                $count += $task->getOutput()->getResult()->getCountByMessage($this->getMessage());
            }
        }

        return $count;
    }

    public function sortByUrl()
    {
        $this->sortFromIndex($this->getUrlSortIndex());
    }

    public function sortByOccurrenceCount()
    {
        $this->sortFromIndex($this->getOccurrenceCountSortIndex());
    }

    /**
     * @param Task[] $tasks
     *
     * @return array
     */
    private function getUrlSortIndex($tasks = null)
    {
        if (is_null($tasks)) {
            $tasks = $this->getTasks();
        }

        $index = [];
        foreach ($tasks as $position => $task) {
            $index[$position] = strtolower($task->getUrl());
        }

        asort($index);
        return $index;
    }

    /**
     * @return array
     */
    private function getOccurrenceCountSortIndex()
    {
        $index = [];

        foreach ($this->getTasks() as $position => $task) {
            $index[$position] = $task->getOutput()->getResult()->getCountByMessage($this->getMessage());
        }

        arsort($index);

        $indexValueFrequency = array_count_values($index);

        $processedCounts = [];
        $indexOffset = -1;

        foreach ($index as $position => $occurrenceCount) {
            $indexOffset++;

            if (in_array($occurrenceCount, $processedCounts)) {
                continue;
            }

            if ($indexValueFrequency[$occurrenceCount] > 1) {
                $tasks = [];
                $subsetKeys = array_flip(array_keys($index, $occurrenceCount));

                foreach ($subsetKeys as $subsetPosition => $count) {
                    $tasks[$subsetPosition] = $this->getTasks()->get($subsetPosition);
                }

                $subsetIndex = $this->getUrlSortIndex($tasks);
                $index = $this->mergeSubsetIndex($index, array_keys($subsetIndex), $indexOffset);
            }

            $processedCounts[] = $occurrenceCount;
        }

        return $index;
    }

    /**
     * @param array $index
     * @param array $subsetIndex
     * @param int $offset
     *
     * @return array
     */
    private function mergeSubsetIndex($index, $subsetIndex, $offset)
    {
        $newIndex = [];
        $indexOffset = 0;

        foreach ($index as $position => $value) {
            if ($indexOffset < $offset || $indexOffset > $offset + count($subsetIndex) - 1) {
                $newIndex[$position] = $value;
            } else {
                $newIndex[$subsetIndex[$indexOffset - $offset]] = $value;
            }

            $indexOffset++;
        }

        return $newIndex;
    }

    /**
     * @param $index
     */
    private function sortFromIndex($index)
    {
        $unsortedTasks = clone $this->getTasks();
        $this->tasks = new ArrayCollection();

        foreach ($index as $position => $value) {
            $this->addTask($unsortedTasks->get($position));
        }
    }
}
