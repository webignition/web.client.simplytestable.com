<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;


abstract class CoreApplicationService {    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Model\User;
     */
    private static $user;
    
    
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
    
    
    /**
     *
     * @var \webignition\Http\Client\Client
     */
    private $httpClient;
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService
    ) {
        $this->parameters = $parameters;
        $this->webResourceService = $webResourceService;
        $this->httpClient = $webResourceService->getHttpClient();
    } 
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     */
    public function setUser(User $user) {
        self::$user = $user;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function getUser() {
        return self::$user;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasUser() {
        return !is_null($this->getUser());
    }
    
    
    protected function getUrl($name = null, $parameters = null) {
        $url = $this->parameters['urls']['base'];
        
        if (!is_null($name)) {
            $url .= $this->parameters['urls'][$name];
        }
        
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
            'Authorization' => 'Basic ' . base64_encode($this->getUser()->getUsername().':'.$this->getUser()->getPassword())
        ));
        
        return $httpRequest;
    }
    
    
    /**
     * 
     * @return \webignition\Http\Client\Client
     */
    protected function getHttpClient() {
        return $this->httpClient;
    }
    
}