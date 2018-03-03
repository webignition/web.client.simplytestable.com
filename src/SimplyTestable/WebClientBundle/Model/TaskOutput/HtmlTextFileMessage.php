<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class HtmlTextFileMessage extends TextFileMessage
{
    /**
     * @var int
     */
    private $columnNumber;

    /**
     * @param int $columnNumber
     */
    public function setColumnNumber($columnNumber)
    {
        $this->columnNumber = $columnNumber;
    }

    /**
     * @return int
     */
    public function getColumnNumber()
    {
        return $this->columnNumber;
    }
}
