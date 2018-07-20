<?php

namespace AppBundle\Model\TaskOutput;

class CssTextFileMessage extends TextFileMessage
{
    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    private $ref;

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    /**
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }
}
