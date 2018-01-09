<?php
namespace SimplyTestable\WebClientBundle\Services;

use webignition\InternetMediaType\InternetMediaType;
use webignition\InternetMediaType\Parser\Parser as InternetMediaTypeParser;
use Negotiation\FormatNegotiator;
use Symfony\Component\HttpFoundation\Request;

class ResponseFormatService
{
    const DEFAULT_RESPONSE_FORMAT = 'text/html';

    /**
     * @var array
     */
    private $requestFormatAttributeToContentTypeMap = [
        'json' => 'application/json'
    ];

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string[]
     */
    private $allowedContentTypes = [];

    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return InternetMediaType
     */
    public function getRequestedResponseFormat()
    {
        $parser = new InternetMediaTypeParser();
        $parser->getConfiguration()->enableAttemptToRecoverFromInvalidInternalCharacter();
        $parser->getConfiguration()->enableIgnoreInvalidAttributes();

        return $parser->parse($this->getRequestedResponseContentTypeString());
    }

    /**
     * @return string
     */
    private function getRequestedResponseContentTypeString()
    {
        if ($this->hasValidRequestFormatAttribute()) {
            return $this->requestFormatAttributeToContentTypeMap[$this->request->attributes->get('_format')];
        }

        if (!$this->request->headers->has('accept')) {
            return self::DEFAULT_RESPONSE_FORMAT;
        }

        $negotiator = new FormatNegotiator();
        $priorities   = ['text/html', 'application/json', '*/*'];
        $format = $negotiator->getBest($this->request->headers->get('accept'), $priorities);

        return $format->getValue();
    }

    /**
     * @return bool
     */
    private function hasValidRequestFormatAttribute()
    {
        $hasFormatAttribute = $this->request->attributes->has('_format');

        return $hasFormatAttribute && array_key_exists(
            $this->request->attributes->get('_format'),
            $this->requestFormatAttributeToContentTypeMap
        );
    }

    /**
     * @param array $allowedContentTypes
     */
    public function setAllowedContentTypes(array $allowedContentTypes = [])
    {
        $this->allowedContentTypes = $allowedContentTypes;
    }

    /**
     * @return bool
     */
    public function hasAllowedResponseFormat()
    {
        if ($this->responseFormatMatchesRequestFormatAttribute()) {
            return true;
        }

        return in_array($this->getRequestedResponseFormat(), $this->allowedContentTypes);
    }

    /**
     * @return bool
     */
    private function responseFormatMatchesRequestFormatAttribute()
    {
        if (!$this->hasValidRequestFormatAttribute()) {
            return false;
        }

        $requestFormat = $this->request->attributes->get('_format');

        return $this->requestFormatAttributeToContentTypeMap[$requestFormat] == $this->getRequestedResponseFormat();
    }

    /**
     * @return bool
     */
    public function isDefaultResponseFormat()
    {
        return $this->getRequestedResponseFormat() == self::DEFAULT_RESPONSE_FORMAT;
    }
}
