<?php
namespace SimplyTestable\WebClientBundle\Services;

use webignition\InternetMediaType\Parser\Parser as InternetMediaTypeParser;
use Negotiation\FormatNegotiator;
use Symfony\Component\HttpFoundation\Request;

class ResponseFormatService {

    const DEFAULT_RESPONSE_FORMAT = 'text/html';

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
    private $allowedContentTypes = array();


    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null) {
        if ($request instanceof Request) {
            $this->request = $request;
        }
    }


    /**
     * @return \webignition\InternetMediaType\InternetMediaType
     */
    public function getRequestedResponseFormat() {
        $parser = new InternetMediaTypeParser();
        $parser->getConfiguration()->enableAttemptToRecoverFromInvalidInternalCharacter();
        $parser->getConfiguration()->enableIgnoreInvalidAttributes();

        return $parser->parse($this->getRequestedResponseContentTypeString());
    }


    /**
     * @return string
     */
    private function getRequestedResponseContentTypeString() {
        if ($this->hasValidRequestFormatAttribute()) {
            return $this->requestFormatAttributeToContentTypeMap[$this->request->attributes->get('_format')];
        }

        if (!$this->request->headers->has('accept')) {
            return self::DEFAULT_RESPONSE_FORMAT;
        }

        $negotiator = new FormatNegotiator();
        $priorities   = array('text/html', 'application/json', '*/*');
        $format = $negotiator->getBest($this->request->headers->get('accept'), $priorities);

        return $format->getValue();
    }


    /**
     * @return bool
     */
    private function hasValidRequestFormatAttribute() {
        return $this->request->attributes->has('_format') && array_key_exists($this->request->attributes->get('_format'), $this->requestFormatAttributeToContentTypeMap);
    }


    /**
     * @param array $allowedContentTypes
     */
    public function setAllowedContentTypes(array $allowedContentTypes = array()) {
        $this->allowedContentTypes = $allowedContentTypes;
    }


    /**
     * @return bool
     */
    public function hasAllowedResponseFormat() {
        if ($this->responseFormatMatchesRequestFormatAttribute()) {
            return true;
        }

        return in_array($this->getRequestedResponseFormat(), $this->allowedContentTypes);
    }


    /**
     * @return bool
     */
    private function responseFormatMatchesRequestFormatAttribute() {
        if (!$this->request->attributes->has('_format')) {
            return false;
        }

        if (!array_key_exists($this->request->attributes->get('_format'), $this->requestFormatAttributeToContentTypeMap)) {
            return false;
        }

        return $this->requestFormatAttributeToContentTypeMap[$this->request->attributes->get('_format')] == $this->getRequestedResponseFormat();
    }


    /**
     * @return bool
     */
    public function isDefaultResponseFormat() {
        return $this->getRequestedResponseFormat() == self::DEFAULT_RESPONSE_FORMAT;
    }

}