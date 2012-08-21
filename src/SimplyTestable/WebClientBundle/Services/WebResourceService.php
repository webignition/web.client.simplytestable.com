<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Exception\WebResourceServiceException;
use webignition\WebResource\WebResource;
use webignition\InternetMediaType\Parser\Parser as InternetMediaTypeParser;

class WebResourceService {
    
    /**
     *
     * @var \webignition\Http\Client\Client
     */
    private $httpClient; 
    
    
    /**
     * Maps content types to WebResource subclasses
     * 
     * @var array
     */
    private $contentTypeWebResourceMap = array();
    
    
    /**
     *
     * @param \webignition\Http\Client\Client $httpClient
     * @param array $contentTypeWebResourceMap
     */
    public function __construct(
            \webignition\Http\Client\Client $httpClient,
            $contentTypeWebResourceMap)
    {
        $this->httpClient = $httpClient;
        $this->httpClient->redirectHandler()->enable();
        $this->httpClient->sender()->setRetryLimit(10);
        
        $this->contentTypeWebResourceMap = $contentTypeWebResourceMap;        
    }
    
    
    /**
     *
     * @param \HttpRequest $request
     * @return \webignition\WebResource\WebResource 
     * @throws \webignition\Http\Client\CurlException
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceServiceException
     */
    public function get($request) {
        $response = $this->httpClient->getResponse($request);
        $responseCodeClass = substr($response->getResponseCode(), 0, 1);
        
        if ($responseCodeClass == 4 || $responseCodeClass == 5) {
            throw new WebResourceServiceException($response->getResponseCode());
        }
        
        $mediaTypeParser = new InternetMediaTypeParser();
        $contentType = $mediaTypeParser->parse($response->getHeader('content-type'));
        
        $webResourceClassName = $this->getWebResourceClassName($contentType->getTypeSubtypeString());

        $resource = new $webResourceClassName;
        $resource->setContent($response->getBody());
        $resource->setContentType($response->getHeader('content-type'));
        $resource->setUrl($request->getUrl());       
        
        return $resource;
    }
    

    /**
     * Get the WebResource subclass name for a given content type
     * 
     * @param string $contentType
     * @return string
     */
    private function getWebResourceClassName($contentType) {
        return (isset($this->contentTypeWebResourceMap[$contentType])) ? $this->contentTypeWebResourceMap[$contentType] : $this->contentTypeWebResourceMap['default'];
    }
    
}