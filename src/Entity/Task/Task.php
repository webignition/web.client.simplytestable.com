<?php

namespace App\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Test\Test;
use App\Entity\TimePeriod;
use App\Entity\Task\Output as TaskOutput;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    const STATE_CANCELLED = 'cancelled';
    const STATE_QUEUED = 'queued';
    const STATE_IN_PROGRESS = 'in-progress';
    const STATE_COMPLETED = 'completed';
    const STATE_AWAITING_CANCELLATION = 'awaiting-cancellation';
    const STATE_QUEUED_FOR_ASSIGNMENT = 'queued-for-assignment';
    const STATE_FAILED_NO_RETRY_AVAILABLE = 'failed-no-retry-available';
    const STATE_FAILED_RETRY_AVAILABLE = 'failed-retry-available';
    const STATE_FAILED_RETRY_LIMIT_REACHED = 'failed-retry-limit-reached';
    const STATE_SKIPPED = 'skipped';
    const STATE_FAILED = 'failed';

    const TYPE_HTML_VALIDATION = 'HTML validation';
    const TYPE_CSS_VALIDATION = 'CSS validation';
    const TYPE_JS_STATIC_ANALYSIS = 'JS static analysis';
    const TYPE_LINK_INTEGRITY = 'Link integrity';

    const TYPE_KEY_HTML_VALIDATION = 'html-validation';
    const TYPE_KEY_CSS_VALIDATION = 'css-validation';
    const TYPE_KEY_JS_STATIC_ANALYSIS = 'js-static-analysis';
    const TYPE_KEY_LINK_INTEGRITY = 'link-integrity';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $taskId;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $worker;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @var TimePeriod
     *
     * @ORM\OneToOne(targetEntity="App\Entity\TimePeriod", cascade={"persist"})
     */
    private $timePeriod;

    /**
     * @var TaskOutput
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Task\Output")
     */
    private $output;

    /**
     * @var Test
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Test\Test", inversedBy="tasks")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id", nullable=false)
     */
    protected $test;


    public function __construct()
    {
        $this->timePeriod = new TimePeriod();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $url
     *
     * @return Task
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getNormalisedUrl()
    {
        $url = (string)$this->getUrl();
        if ($url == '') {
            return $url;
        }

        $normalisedUrl = new NormalisedUrl($url);
        return (string)$normalisedUrl;
    }

    /**
     * @param string $state
     *
     * @return Task
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $worker
     *
     * @return Task
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * @return string
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * @param string $type
     *
     * @return Task
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param TimePeriod $timePeriod
     *
     * @return Task
     */
    public function setTimePeriod(TimePeriod $timePeriod = null)
    {
        $this->timePeriod = $timePeriod;

        return $this;
    }

    /**
     * @return TimePeriod
     */
    public function getTimePeriod()
    {
        return $this->timePeriod;
    }

    /**
     * @param TaskOutput $output
     *
     * @return Task
     */
    public function setOutput(TaskOutput $output = null)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return TaskOutput
     */
    public function getOutput()
    {
        if (self::TYPE_JS_STATIC_ANALYSIS === $this->type) {
            return null;
        }

        return $this->output;
    }

    /**
     * @param Test $test
     *
     * @return Task
     */
    public function setTest(Test $test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * @return Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param integer $taskId
     *
     * @return Task
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @return bool
     */
    public function hasOutput()
    {
        return !is_null($this->getOutput());
    }

    /**
     * @return string
     */
    public function getFormattedUrl()
    {
        return rawurldecode($this->getUrl());
    }

    /**
     * @return bool
     */
    public function getStateLabel()
    {
        $isFailed = $this->isStateInSet([
            self::STATE_FAILED,
            self::STATE_FAILED_NO_RETRY_AVAILABLE,
            self::STATE_FAILED_RETRY_AVAILABLE,
            self::STATE_FAILED_RETRY_LIMIT_REACHED,
        ]);

        if ($isFailed) {
            return self::STATE_FAILED;
        }

        $isQueued = $this->isStateInSet([
            self::STATE_QUEUED,
            self::STATE_QUEUED_FOR_ASSIGNMENT,
        ]);

        if ($isQueued) {
            return self::STATE_QUEUED;
        }

        return $this->state;
    }

    /**
     * @param string[] $stateSet
     *
     * @return bool
     */
    private function isStateInSet(array $stateSet)
    {
        return in_array($this->state, $stateSet);
    }
}
