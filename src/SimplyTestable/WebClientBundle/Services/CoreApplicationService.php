<?php
namespace SimplyTestable\WebClientBundle\Services;


abstract class CoreApplicationService {    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\WebResourceService 
     */
    protected $webResourceService;
    
    
    /**
     *
     * @var array
     */
    private $parameters;
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService
    ) {
        $this->parameters = $parameters;
        $this->webResourceService = $webResourceService;        
    }   
    
    
    protected function getUrl($name, $parameters) {
        $url =  $this->parameters['urls']['base'] . $this->parameters['urls'][$name];
        
        if (is_array($parameters)) {
            foreach ($parameters as $parameterName => $parameterValue) {
                $url = str_replace('{'.$parameterName.'}', $parameterValue, $url);
            }
        }
        
        return $url;
    }
    
    
    protected function getAuthorisedHttpRequest($url = '', $request_method = HTTP_METH_GET, $options = array()) {
        $httpRequest = new \HttpRequest($url, $request_method, $options);
        $httpRequest->addHeaders(array(
            'Authorization' => 'Basic ' . base64_encode('public:public')
        ));
        
        return $httpRequest;
    }
    
}