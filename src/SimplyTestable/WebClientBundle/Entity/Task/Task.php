<?php

namespace SimplyTestable\WebClientBundle\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use SimplyTestable\WebClientBundle\Entity\Task\Output as TaskOutput;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 * @ORM\Entity
 * @SerializerAnnotation\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="SimplyTestable\WebClientBundle\Repository\TaskRepository")
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

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @SerializerAnnotation\Expose
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $taskId;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SerializerAnnotation\Expose
     */
    private $worker;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $type;

    /**
     * @var TimePeriod
     *
     * @ORM\OneToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\TimePeriod", cascade={"persist"})
     * @SerializerAnnotation\Expose
     */
    private $timePeriod;

    /**
     * @var TaskOutput
     *
     * @ORM\ManyToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\Task\Output")
     * @SerializerAnnotation\Expose
     */
    private $output;

    /**
     * @var Test
     *
     * @ORM\ManyToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\Test\Test", inversedBy="tasks")
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
}
