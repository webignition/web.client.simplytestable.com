<?php
namespace SimplyTestable\WebClientBundle\Services;

class UrlCheckerService {        
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\WebResourceService 
     */
    protected $webResourceService;
    
    public function __construct(\SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService) {
        $this->webResourceService = $webResourceService;
    }
    
    
    /**
     * 
     * @param string $url
     * @return boolean
     */
    public function exists($url) {
        try {
            $this->webResourceService->getHttpClientService()->get()->send(
                $this->webResourceService->getHttpClientService()->headRequest($url)
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }          
    }

    
}