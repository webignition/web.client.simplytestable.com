<?php

namespace AppBundle\Model\TaskOutput;

class Result
{
    const OUTCOME_PASSED = 'passed';
    const OUTCOME_FAILED = 'failed';

    /**
     * @var Message[]
     */
    private $messages = [];

    /**
     * @param Message $message
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @return Message[]
     */
    public function getErrors()
    {
        return $this->getMessagesOfType(Message::TYPE_ERROR);
    }

    /**
     * @param string $message
     *
     * @return int
     */
    public function getCountByMessage($message)
    {
        $comparator = strtolower($message);
        $count = 0;

        foreach ($this->getErrors() as $error) {
            if (strtolower($error->getMessage()) == $comparator) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return Message[]
     */
    public function getWarnings()
    {
        return $this->getMessagesOfType(Message::TYPE_WARNING);
    }

    /**
     * @param string
     *
     * @return Message[]
     */
    private function getMessagesOfType($type)
    {
        $messages = array();

        foreach ($this->messages as $message) {
            /* @var $message Message */
            if ($message->getType() == $type) {
                $messages[] = $message;
            }
        }

        return $messages;
    }

    /**
     * @return Message
     */
    public function getFirstError()
    {
        $errors = $this->getErrors();

        return $errors[0];
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        return count($this->getErrors());
    }

    /**
     * @return int
     */
    public function getWarningCount()
    {
        return count($this->getWarnings());
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->getErrorCount() > 0;
    }

    /**
     * @return bool
     */
    public function hasWarnings()
    {
        return $this->getWarningCount() > 0;
    }

    /**
     * @return string
     */
    public function getOutcome()
    {
        return $this->hasErrors()
            ? self::OUTCOME_FAILED
            : self::OUTCOME_PASSED;
    }

    /**
     * @return bool
     */
    public function isHtmlMissingDocumentTypeFailure()
    {
        return $this->isOfErrorClass('/document-type-missing/');
    }

    /**
     * @return bool
     */
    public function isHtmlInvalidDocumentTypeFailure()
    {
        return $this->isOfErrorClass('/document-type-invalid/');
    }

    /**
     * @return bool
     */
    public function isMarkuplessTextHtmlFailure()
    {
        return $this->isOfErrorClass('/document-is-not-markup/');
    }

    /**
     * @return bool
     */
    public function isHttpRedirectLoopFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-redirect-loop/');
    }

    /**
     * @return bool
     */
    public function isHttpRedirectLimitFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-redirect-limit-reached/');
    }

    /**
     * @return bool
     */
    public function isCharacterEncodingFailure()
    {
        return $this->isOfErrorClass('/character-encoding/');
    }

    /**
     * @return bool
     */
    public function isCurlTimeoutFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-28/');
    }

    /**
     * @return bool
     */
    public function isCurlDnsResolutionFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-6/');
    }

    /**
     * @return bool
     */
    public function isCurlUrlFormatFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-3$/');
    }

    /**
     * @return bool
     */
    public function isCurlSslFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-35$/');
    }

    /**
     * @return bool
     */
    public function isHttpClientErrorFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-4\d\d/');
    }

    /**
     * @return bool
     */
    public function isHttpServerErrorFailure()
    {
        return $this->isOfErrorClass('/http-retrieval-5\d\d/');
    }

    /**
     * @return bool
     */
    public function isHttpClientOrServerErrorFailure()
    {
        return $this->isHttpClientErrorFailure() || $this->isHttpServerErrorFailure();
    }

    /**
     * @return bool
     */
    public function isValidatorServerErrorFailure()
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return false;
        }

        return $errors[0]->getClass() == 'validator-internal-server-error';
    }

    /**
     * @return bool
     */
    public function isCssValidationUnknownExceptionError()
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return false;
        }

        return $errors[0]->getMessage() == 'Unknown error';
    }

    /**
     * @return bool
     */
    public function isCssValidationSslExceptionError()
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return false;
        }

        return $errors[0]->getMessage() == 'SSL Error';
    }

    /**
     * @param string $errorClassPattern
     *
     * @return bool
     */
    private function isOfErrorClass($errorClassPattern)
    {
        foreach ($this->getErrors() as $error) {
            if (preg_match($errorClassPattern, $error->getClass()) > 0) {
                return true;
            }
        }

        return false;
    }
}
