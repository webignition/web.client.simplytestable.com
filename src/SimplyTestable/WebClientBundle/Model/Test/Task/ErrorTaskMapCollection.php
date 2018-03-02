<?php
namespace SimplyTestable\WebClientBundle\Model\Test\Task;

use SimplyTestable\WebClientBundle\Entity\Task\Task;

class ErrorTaskMapCollection
{
    /**
     * @var Task[]
     */
    private $tasks = [];

    /**
     * @var ErrorTaskMap[]
     */
    private $errorTaskMaps;

    /**
     * @param Task[] $tasks
     */
    public function __construct($tasks = [])
    {
        $this->setTasks($tasks);
    }

    /**
     * @param Task[] $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
        $this->buildErrorPageListMap();

        unset($this->tasks);
    }

    /**
     * @return ErrorTaskMap[]
     */
    public function getErrorTaskMaps()
    {
        return $this->errorTaskMaps;
    }

    private function buildErrorPageListMap()
    {
        $this->errorTaskMaps = [];

        foreach ($this->tasks as $task) {
            foreach ($task->getOutput()->getResult()->getErrors() as $error) {
                if (!$this->hasMapForMessage($error->getMessage())) {
                    $this->errorTaskMaps[] = new ErrorTaskMap($error->getMessage());
                }

                $errorTaskMap = $this->getMapForMessage($error->getMessage());
                $errorTaskMap->addTask($task);
            }
        }
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    private function hasMapForMessage($message)
    {
        return !is_null($this->getMapForMessage($message));
    }


    /**
     * @param $message
     *
     * @return null|ErrorTaskMap
     */
    private function getMapForMessage($message)
    {
        foreach ($this->errorTaskMaps as $errorTaskMap) {
            if ($errorTaskMap->match($message)) {
                return $errorTaskMap;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    public function getUniqueErrorCount()
    {
        return count($this->errorTaskMaps);
    }

    public function sortMapsByUrl()
    {
        foreach ($this->getErrorTaskMaps() as $errorTaskMap) {
            $errorTaskMap->sortByUrl();
        }
    }

    public function sortMapsByOccurrenceCount()
    {
        foreach ($this->getErrorTaskMaps() as $errorTaskMap) {
            $errorTaskMap->sortByOccurrenceCount();
        }
    }

    public function sortByOccurrenceCount()
    {
        $this->sortFromIndex($this->getOccurrenceCountSortIndex());
    }

    /**
     * @param array $index
     */
    private function sortFromIndex($index)
    {
        $unsortedErrorTaskMaps = $this->getErrorTaskMaps();
        $this->errorTaskMaps = [];

        foreach ($index as $position => $value) {
            $this->errorTaskMaps[] = $unsortedErrorTaskMaps[$position];
        }
    }

    /**
     * @param string[] $messages
     *
     * @return array
     */
    private function getMessageSortIndex($messages)
    {
        $index = [];
        foreach ($messages as $position => $message) {
            $index[$position] = strtolower($message);
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

        foreach ($this->getErrorTaskMaps() as $position => $errorTaskMap) {
            $index[$position] = $errorTaskMap->getCumulativeOccurrenceCount();
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
                $errors = [];
                $subsetKeys = array_flip(array_keys($index, $occurrenceCount));

                foreach ($subsetKeys as $subsetPosition => $count) {
                    $errors[$subsetPosition] = $this->errorTaskMaps[$subsetPosition]->getMessage();
                }

                $subsetIndex = $this->getMessageSortIndex($errors);
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
}
