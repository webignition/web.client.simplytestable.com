<?php

namespace SimplyTestable\WebClientBundle\Services;

use webignition\InternetMediaType\Parser\Parser as InternetMediaTypeParser;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;

class WebResourceService {    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\HttpClientService
     */
    private $httpClientService;
    
    
    /**
     * Maps content types to WebResource subclasses
     * 
     * @var array
     */
    private $contentTypeWebResourceMap = array();
    
    
    /**
     *
     * @param \SimplyTestable\WebClientBundle\Services\HttpClientService $httpClientService
     * @param array $contentTypeWebResourceMap
     */
    public function __construct(
            \SimplyTestable\WebClientBundle\Services\HttpClientService $httpClientService,
            $contentTypeWebResourceMap)
    {
        $this->httpClientService = $httpClientService;        
        $this->contentTypeWebResourceMap = $contentTypeWebResourceMap;        
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\HttpClientService
     */
    public function getHttpClientService() {        
        return $this->httpClientService;
    }
    
    
    /**
     * 
     * @param \Guzzle\Http\Message\Request $request
     * @return \SimplyTestable\WebClientBundle\Services\webResourceClassName
     * @throws WebResourceException
     */
    public function get(\Guzzle\Http\Message\Request $request) {        
        // Guzzle seems to be flailing in errors if redirects total more than 4
        $request->getParams()->set('redirect.max', 4);
        
        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\ServerErrorResponseException $serverErrorResponseException) {
            $response = $serverErrorResponseException->getResponse();
        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $clientErrorResponseException) {
            $response = $clientErrorResponseException->getResponse();
        }
        
        if ($response->isInformational()) {
            // Interesting to see what makes this happen
            return;
        }
        
        if ($response->isRedirect()) {
            // Shouldn't happen, HTTP client should have the redirect handler
            // enabled, redirects should be followed            
            return;
        }
        
//        echo $request->getUrl() . "\n";
//        echo $response . "\n\n";


        
        if ($response->isClientError() || $response->isServerError()) {
            throw new WebResourceException($response, $request); 
        }
        
        $mediaTypeParser = new InternetMediaTypeParser();
        $mediaTypeParser->setIgnoreInvalidAttributes(true);
        $contentType = $mediaTypeParser->parse($response->getContentType());               

        $webResourceClassName = $this->getWebResourceClassName($contentType->getTypeSubtypeString());

        $resource = new $webResourceClassName;                
        $resource->setContent($response->getBody(true));                              
        $resource->setContentType((string)$contentType);                  
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