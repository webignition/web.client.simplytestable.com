<?php

namespace AppBundle\Model\TaskOutput;

class TextFileMessage extends Message
{
    /**
     * @var int
     */
    private $lineNumber;

    /**
     * @param int $lineNumber
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
    }

    /**
     * @return int
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }
}
