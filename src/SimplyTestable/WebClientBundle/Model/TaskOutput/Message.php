<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

abstract class Message
{
    const TYPE_ERROR = 'error';
    const TYPE_NOTICE = 'notice';
    const TYPE_WARNING = 'warning';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var string
     */
    private $class = '';

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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getMessage();
    }
}
