<?php

namespace App\Model\TaskOutput;

class Result
{
    const OUTCOME_PASSED = 'passed';
    const OUTCOME_FAILED = 'failed';

    /**
     * @var Message[]
     */
    private $messages = [];

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @return Message[]
     */
    public function getErrors(): array
    {
        return $this->getMessagesOfType(Message::TYPE_ERROR);
    }

    public function getCountByMessage(string $message): int
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
    public function getWarnings(): array
    {
        return $this->getMessagesOfType(Message::TYPE_WARNING);
    }

    /**
     * @param string $type
     *
     * @return Message[]
     */
    private function getMessagesOfType(string $type): array
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

    public function getFirstError(): ?Message
    {
        $errors = $this->getErrors();

        return $errors[0];
    }

    public function getErrorCount(): int
    {
        return count($this->getErrors());
    }

    public function getWarningCount(): int
    {
        return count($this->getWarnings());
    }

    public function hasErrors(): bool
    {
        return $this->getErrorCount() > 0;
    }

    public function hasWarnings(): bool
    {
        return $this->getWarningCount() > 0;
    }

    public function getOutcome(): string
    {
        return $this->hasErrors()
            ? self::OUTCOME_FAILED
            : self::OUTCOME_PASSED;
    }

    public function isHtmlMissingDocumentTypeFailure(): bool
    {
        return $this->isOfErrorClass('/document-type-missing/');
    }

    public function isHtmlInvalidDocumentTypeFailure(): bool
    {
        return $this->isOfErrorClass('/document-type-invalid/');
    }

    public function isMarkuplessTextHtmlFailure(): bool
    {
        return $this->isOfErrorClass('/document-is-not-markup/');
    }

    public function isHttpRedirectLoopFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-redirect-loop/');
    }

    public function isHttpRedirectLimitFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-redirect-limit-reached/');
    }

    public function isInvalidCharacterEncodingFailure(): bool
    {
        return $this->isOfErrorClass('/invalid-character-encoding/');
    }

    public function isCurlTimeoutFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-28/');
    }

    public function isCurlDnsResolutionFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-6/');
    }

    public function isCurlUrlFormatFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-3$/');
    }

    public function isCurlSslFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-curl-code-35$/');
    }

    public function isHttpClientErrorFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-4\d\d/');
    }

    public function isHttpServerErrorFailure(): bool
    {
        return $this->isOfErrorClass('/http-retrieval-5\d\d/');
    }

    public function isHttpClientOrServerErrorFailure(): bool
    {
        return $this->isHttpClientErrorFailure() || $this->isHttpServerErrorFailure();
    }

    public function isValidatorServerErrorFailure(): bool
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return false;
        }

        return $errors[0]->getClass() == 'validator-internal-server-error';
    }

    public function isCssValidationUnknownExceptionError(): bool
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return false;
        }

        return $errors[0]->getMessage() == 'Unknown error';
    }

    public function isCssValidationSslExceptionError(): bool
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return false;
        }

        return $errors[0]->getMessage() == 'SSL Error';
    }

    private function isOfErrorClass(string $errorClassPattern): bool
    {
        foreach ($this->getErrors() as $error) {
            if (preg_match($errorClassPattern, $error->getClass()) > 0) {
                return true;
            }
        }

        return false;
    }
}
