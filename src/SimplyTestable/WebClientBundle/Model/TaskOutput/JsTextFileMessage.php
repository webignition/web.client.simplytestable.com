<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class JsTextFileMessage extends TextFileMessage
{
    const FRAGMENT_TRUNCATION_LENGTH = 120;

    /**
     * @var string
     */
    private $context;

    /**
     * @var int
     */
    private $columnNumber;

    /**
     * @var string
     */
    private $fragment;

    /**
     * @return bool
     */
    public function isException()
    {
        return preg_match('/^[a-zA-z]+Exception$/', $this->fragment);
    }

    /**
     * @return bool
     */
    public function isContentTypeException()
    {
        return 'InvalidContentTypeException' === $this->getFragment();
    }

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
     * @param string $fragment
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @return bool
     */
    public function canFragmentBeTruncated()
    {
        return strlen(($this->getFragment())) >= self::FRAGMENT_TRUNCATION_LENGTH;
    }

    /**
     * @return string
     */
    public function getTruncatedFragment()
    {
        return substr($this->getFragment(), 0, self::FRAGMENT_TRUNCATION_LENGTH);
    }

    /**
     * @return bool
     */
    public function isLinterNotice()
    {
        return $this->isLinterStoppingNotice() || $this->isLinterTooManyErrorsNotice();
    }

    /**
     * @return bool
     */
    private function isLinterStoppingNotice()
    {
        return preg_match('/Stopping\. \([0-9]{1,3}% scanned\)\.$/', $this->getMessage()) > 0;
    }

    /**
     * @return bool
     */
    private function isLinterTooManyErrorsNotice()
    {
        return preg_match('/Too many errors\. \([0-9]{1,3}% scanned\)\.$/', $this->getMessage()) > 0;
    }
}
